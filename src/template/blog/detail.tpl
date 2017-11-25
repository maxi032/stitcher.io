{extends 'index.tpl'}

{block 'head' append}
    {css src='blog.scss' inline=true}
    {css src='hljs-github.css' inline=true}
{/block}

{block 'title'}
    {$post.title} -
{/block}

{block 'content'}
    <header class="banner banner__blog">
        {include 'helper/curve.tpl'}
    </header>
    <div class="content__blog">
        <div class="container detail">
            <article class="blog">
                {if isset($post.image)}
                    <img src="{$post.image.src}" srcset="{$post.image.srcset}" sizes="50vw" alt="{$post.image.title}">
                {/if}

                <h1>
                    {$post.title}

                    {if isset($post.date)}
                        <em>{$post.date|date_format:'%Y-%m-%d'}</em>
                    {/if}
                </h1>

                {$post.content}

                {if isset($post.discuss)}
                    {include 'blog/discuss.tpl' discuss=$post.discuss}
                {/if}
            </article>

            <footer>
                <nav class="spacer">
                    {if $browse.prev}
                        {$title = $browse.prev.title}

                        {if isset($browse.prev.teaserTitle)}
                            {$title = $browse.prev.teaserTitle}
                        {/if}
                        <a class="next cta cta--ghost" href="/blog/{$browse.prev.id}">Next: {$title}</a>
                    {/if}

                    {if $browse.next}
                        {$title = $browse.next.title}

                        {if isset($browse.next.teaserTitle)}
                            {$title = $browse.next.teaserTitle}
                        {/if}

                        <a class="prev cta cta--ghost" href="/blog/{$browse.next.id}">Previous: {$title}</a>
                    {/if}
                </nav>
                <nav class="center spacer">
                    <a class="cta cta--link" href="/blog">Show all posts</a>
                </nav>
            </footer>
        </div>
    </div>
{/block}

{block 'footer'}{/block}

{block 'scripts'}
    {js src="js/codeClick.js" inline=true}
    {js src="/js/hljs.min.js" async=true}
{/block}
