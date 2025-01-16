<?php

namespace App\Console\Commands;

use App\Events\EventListenerProvider;
use Domains\Character\Models\CharacterDTO;
use Domains\Character\Services\CreateCharacterService;
use Domains\Episode\Models\EpisodeDTO;
use Domains\Episode\Service\CreateEpisodeService;
use Domains\Location\Models\LocationDTO;
use Domains\Location\Services\CreateLocationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:import-characters',
    description: 'Import all characters from Rick and Morty API'
)]
class ImportCharacterCommand extends Command
{
    const BASE_URL = "https://rickandmortyapi.com/api/character";

    private HttpClientInterface $httpClient;
    private CreateCharacterService $characterService;
    private CreateLocationService $locationService;
    private CreateEpisodeService $episodeService;
    private EventListenerProvider $eventListenerProvider;
    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        EventListenerProvider $eventListenerProvider,
        CreateCharacterService $characterService,
        CreateLocationService $locationService,
        CreateEpisodeService $episodeService,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->httpClient = $httpClient;
        $this->characterService = $characterService;
        $this->locationService = $locationService;
        $this->episodeService = $episodeService;
        $this->eventListenerProvider = $eventListenerProvider;
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Starting character import...");
        $this->eventListenerProvider->registerListeners();

        try {
            $response = $this->httpClient->request('GET', self::BASE_URL);
            $baseData = $response->toArray();
            $totalCharacters = $baseData['info']['count'] ?? 0;
            $totalPages = $baseData['info']['pages'] ?? 0;

            if ($totalCharacters === 0) {
                throw new \RuntimeException('No characters found to import');
            }

            $progressBar = new ProgressBar($output, $totalCharacters);
            $progressBar->setFormat(
                '%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%'
            );
            $progressBar->start();

            for ($page = 1; $page <= $totalPages; $page++) {
                $pageUrl = sprintf('%s?page=%d', self::BASE_URL, $page);
                $pageResponse = $this->httpClient->request('GET', $pageUrl);
                $pageData = $pageResponse->toArray();

                if (isset($pageData['results']) && is_array($pageData['results'])) {
                    $this->processCharacters($pageData['results'], $progressBar);
                }

                usleep(100000);
            }

            $progressBar->finish();
            $output->writeln("\nImport completed successfully!");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Import failed: %s</error>', $e->getMessage()));
            return Command::FAILURE;
        }
    }

    private function processCharacters(array $charactersData, ProgressBar $progressBar): void
    {
        foreach ($charactersData as $characterData) {

            try {
                $characterDTO = new CharacterDTO($characterData);

                $this->processLocation($characterDTO->location()->url());
                $this->processLocation($characterDTO->origin()->url());

                foreach ($characterDTO->episodes() as $episodeURL) {
                    $this->processEpisode($episodeURL);
                }

                $this->characterService->create($characterDTO);

                $progressBar->advance();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), [$e]);
            }
        }
    }

    private function processLocation(?string $url): void
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return;
        }

        try {
            $response = $this->httpClient->request('GET', $url);
            $locationDTO = new LocationDTO($response->toArray());
            $this->locationService->create($locationDTO);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [$e]);
        }
    }

    private function processEpisode(string $url): void
    {
        try {
            $response = $this->httpClient->request('GET', $url);
            $episodeDTO = new EpisodeDTO($response->toArray());
            $this->episodeService->create($episodeDTO);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [$e]);
        }
    }
}