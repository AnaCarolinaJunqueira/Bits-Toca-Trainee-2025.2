const likes = document.querySelector('.likes-content');

if (likes) {
    likes.addEventListener('click', async () => {
        const postId = likes.dataset.postId;
        
        if (!postId) return;

        try {
            const response = await fetch('/post/like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ post_id: postId })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.status === 'success') {
                    if (data.liked) {
                        likes.classList.add('active');
                        likes.querySelector('.likes-icon i').classList.remove('bi-heart');
                        likes.querySelector('.likes-icon i').classList.add('bi-heart-fill');
                    } else {
                        likes.classList.remove('active');
                        likes.querySelector('.likes-icon i').classList.add('bi-heart');
                        likes.querySelector('.likes-icon i').classList.remove('bi-heart-fill');
                    }
                    likes.querySelector('.likes-text p').textContent = data.count;
                } else if (data.message === 'Unauthorized') {
                    window.location.href = '/login';
                }
            } else {
                if (response.status === 401) {
                    window.location.href = '/login';
                }
            }
        } catch (error) {
            console.error('Error liking post:', error);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    
    const editButtons = document.querySelectorAll('.btn-edit-comment');
    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const postId = btn.dataset.postId;
            const content = btn.dataset.conteudo;

            document.getElementById('edit-comment-id').value = id;
            document.getElementById('edit-comment-post-id').value = postId;
            document.getElementById('edit-comment-conteudo').value = content;

            abrirModal('modal-editar-comentario');
        });
    });

    const deleteButtons = document.querySelectorAll('.btn-delete-comment');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const postId = btn.dataset.postId;

            document.getElementById('delete-comment-id').value = id;
            document.getElementById('delete-comment-post-id').value = postId;

            abrirModal('modal-deletar-comentario');
        });
    });
});