<div id="modx-panel-resource-div"></div>

{include file='resource/_javascript.tpl'}
{modblock name='ab'}{/modblock}

{$onDocFormPrerender}
{$onDocFormRender}

{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}