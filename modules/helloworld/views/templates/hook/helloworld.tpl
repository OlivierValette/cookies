{block name="hello_world"}
  <div class="hello_world">
    <h4>{l s='Message' mod='helloworld'}</h4>
    <p>Hello, {if isset($name)} {$name} {else} World {/if}</p>
  </div>
{/block}
