<script>
    export let links, page;

    const urlQueryParams = window.location.search;
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