{extends file='page.tpl'}

{block name='page_content'}
  {block name='hook_home'}
    {widget name="helloworld"}
    {$HOOK_HOME nofilter}
  {/block}
{/block}

