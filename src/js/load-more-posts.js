const getMorePosts = async (page = 1) => {
    if (isNaN(page)) return;
    const request = await fetch(`/wp-json/wuxiadvanced/v1/posts/?page=${page}`);
    const response = await request.json();

    return response;
}

const insertNewPosts = (newPosts) => {
    if (typeof newPosts != 'string') return;
    const postsGrid = document.querySelector('#posts-grid');
    postsGrid.innerHTML += newPosts;
}


export const useLoadMore = () => {
    const loadMoreButton = document.querySelector('#load-more-articles');
    if (!loadMoreButton) return;

    const toggleButtonClickability = () => {
        loadMoreButton.classList.toggle('pointer-events-none');
    }

    const handleButtonClick = async () => {
        const page = loadMoreButton.dataset.page;
        toggleButtonClickability();
        const postsData = await getMorePosts(page);
        if (!postsData.html) throw new Error('There are no more posts');
        insertNewPosts(postsData.html);
        toggleButtonClickability();
        loadMoreButton.dataset.page = Number(page) + 1;
        if (!postsData.has_next) loadMoreButton.remove();
    }

    loadMoreButton.addEventListener('click', handleButtonClick);
}



