<script>
    export let links, page;

    const regex = new RegExp(`[?&]page=[^&]*(&|$)`, 'i');
    const queryParams = window.location.search;
    const urlQueryParams = queryParams.replace(regex, '');
</script>

<ul class="pagination">
    {#each links as link}
        <li class="page-item" class:active={link.label == page}>
            <a class="page-link" class:disabled={!link.url} href={`${link.url}${urlQueryParams}`}>
                {@html link.label}
            </a>
        </li>
    {/each}
</ul>