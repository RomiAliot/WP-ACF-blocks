const resourcesLoadMoreHandler = async (params) => {
  if (typeof params != 'object' || Array.isArray(params)) throw new Error('Use an object for the params');
  const paramsString = Object.entries(params)
    .map(([key, value]) => `${key}=${value}`)
    .join('&');
  const articlesReq = await fetch(`/wp-admin/admin-ajax.php?action=get_more_in_the_news_posts&${paramsString}`);
  const data = await articlesReq.json();
  return data;
}

console.log('funciona')