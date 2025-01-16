export default {
  data() {
    return {
      characters: [],
      page : 1,
      perPageLimit : 10,
      lastPage: 1
    };
  },
  async created() {
    await this.fetchCharacters();
  },

  methods: {
    async fetchCharacters() {
      try {
        const response = await fetch(
          `http://localhost:8080/api/characters?page=${this.page}&perPageLimit=${this.perPageLimit}`
        );
        const result = await response.json();
        console.log("API Response:", result);

        this.characters = result.data;
        this.lastPage = result.lastPage;
      } catch (error) {
        console.error("Error fetching characters:", error);
      }
    },
    async changePage(newPage) {
      this.page = newPage;
      await this.fetchCharacters();
    },
  },
};
