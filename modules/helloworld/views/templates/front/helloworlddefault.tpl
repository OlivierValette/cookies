{extends file=$layout}

{block name='content'}
  <section id="main">
    <h1>{l s='Hello %s.' sprintf=[$name] mod='helloworld'}</h1>
    <p>{l s='Welcome in FrontController' mod='helloworld'}</p>
  </section>
{/block}
