import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8080/api',
});

export default {
  data() {
    return {
      characters: [],
    };
  },
  async created() {
    const response = await api.get('/characters');
    this.characters = response.data;
  },
};
