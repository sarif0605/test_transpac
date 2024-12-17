const articleElement = document.getElementById('articleText');
    let article = articleElement.value;
    function searchWord() {
        const wordToSearch = document.getElementById('searchWord').value.trim();
        const regex = new RegExp(`\\b${wordToSearch}\\b`, 'gi');
        const matches = article.match(regex);
        const count = matches ? matches.length : 0;
        document.getElementById('searchResult').textContent = `"${wordToSearch}" ditemukan ${count} kali.`;
    }
    function replaceWord() {
        const oldWord = document.getElementById('replaceOldWord').value.trim();
        const newWord = document.getElementById('replaceNewWord').value.trim();
        const regex = new RegExp(`\\b${oldWord}\\b`, 'gi');
        article = article.replace(regex, newWord);
        articleElement.value = article;
        document.getElementById('replaceResult').textContent = `Kata "${oldWord}" telah diganti dengan "${newWord}".`;
    }

    function sortWords() {
        const words = article.split(/\W+/).filter(Boolean);
        const sortedWords = words.sort((a, b) => a.toLowerCase().localeCompare(b.toLowerCase()));
        document.getElementById('sortedWords').textContent = `Kata-kata terurut: ${sortedWords.join(', ')}`;
    }