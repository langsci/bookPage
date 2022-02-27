{strip}
    {if $editors}
        {foreach from=$editors key=id item=editor}
            {if $editor@first}
                {$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}
            {elseif $editor@last && $editor@total != 1}
                {" & "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}
                {if $editor@total != 1}{" (eds.)"}
                {else}{" (ed.)"}{/if}
            {else}
                {", "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}
            {/if}
        {/foreach}
    {else}
        {if $authors}
            {foreach from=$authors key=id item=author}
                {if $author@first}
                    {$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {elseif $author@last && $author@total != 1}
                    {" & "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {else}
                    {", "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {/if}
            {/foreach}
        {/if}
    {/if}
        {". "}{if $publication->getData('datePublished')}
                {$publication->getData('datePublished')|date_format:"%Y"}
            {else}
                {if $pubState}
                    {$pubStateLabel}
                {else}
                    {"forthcoming"}
                {/if}
            {/if}
        {". "}
            {if $pubState}
                {$pubStateLabel}{$publication->getLocalizedData('prefix')|regex_replace:"/Forthcoming: |Superseded: /":""}{" "}{$publication->getLocalizedData('title')}
            {else}
                {$publication->getLocalizedData('prefix')}{" "}{$publication->getLocalizedData('title')}
            {/if}
        {if $publication->getLocalizedData('subtitle')}
            {": "}{$publication->getLocalizedData('subtitle')}
        {/if}
    {if $series}
        {". ("}{$series->getLocalizedData('title')}
            {if $publication->getLocalizedData('seriesPosition')}
                {" "}{$publication->getLocalizedData('seriesPosition')}
            {/if}
        {")"}
    {/if}
    {". "}{$currentPress->getData('location')}{": "}{$currentPress->getLocalizedData('name')}{"."}
    {if $publication->getData('pub-id::doi')}
        {" DOI: "}{$publication->getData('pub-id::doi')}
    {elseif count($publicationFormats)}
        {foreach from=$publicationFormats item="publicationFormat"}
            {if $publicationFormat->getIsApproved()}
                {foreach from=$pubIdPlugins item=pubIdPlugin}
                    {assign var=pubIdType value=$pubIdPlugin->getPubIdType()}
                    {assign var=storedPubId value=$publicationFormat->getStoredPubId($pubIdType)}
                    {if $pubIdType == 'doi' && $storedPubId}
                        {" DOI: "}{$storedPubId|escape}
                        {break}
                    {/if}
                {/foreach}
            {/if}
        {/foreach}
    {/if}
{/strip}