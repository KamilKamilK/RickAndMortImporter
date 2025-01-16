<template>
  <div>
    <h1>Rick and Morty Character List</h1>
    <table>
      <thead>
      <tr>
        <th>Name</th>
        <th>Status</th>
        <th>Species</th>
        <th>Localization</th>
        <th>Origin</th>
        <th>Episode</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="character in characters" :key="character.name">
        <td>{{ character.name }}</td>
        <td>{{ character.status }}</td>
        <td>{{ character.species }}</td>
        <td>{{ character.localization || "Unknown" }}</td>
        <td>{{ character.origin || "Unknown" }}</td>
        <td>{{ character.episode }}</td>
      </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button
        class="pagination-button"
        :disabled="page <=1"
        @click="changePage(page - 1)">
        Previous
      </button>
      <span>Page {{ page }} of {{ lastPage }}</span>
      <button
        class="pagination-button"
        :disabled="page >= lastPage"
        @click="changePage(page + 1)">
        Next
      </button>
    </div>
  </div>
</template>

<script>
import characterListMixin from '@/services/characterList';

export default {
  mixins: [characterListMixin],
};
</script>
