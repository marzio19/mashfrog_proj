const tweets = document.getElementById('tweets')

if(tweets) {
    tweets.addEventListener('click', (e) => {
        if(e.target.className === 'btn btn-danger delete-tweet') {
            if(confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch('/tweet/delete/${id}',{
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}