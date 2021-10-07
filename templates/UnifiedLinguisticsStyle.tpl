{strip}
    {if $editors}
        {foreach from=$editors key=id item=editor}
            {if $editor@first}
                {$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" (ed.)"}
            {elseif $editor@last && $editor@total != 1}
                {"& "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" (ed.)"}
            {else}
                {", "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" (ed.)"}
            {/if}
        {/foreach}
    {else}
        {if $authors}
            {foreach from=$authors key=id item=author}
                {if $author@first}
                    {$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {elseif $author@last && $author@total != 1}
                    {"& "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {else}
                    {", "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {/if}
            {/foreach}
        {/if}
    {/if}
        {" . "}{$publication->getData('datePublished')|date_format:"%Y"} 
        {" . "}{$publication->getLocalizedData('title')}
        {if $publication->getLocalizedData('subtitle')}
            {" : "}{$publication->getLocalizedData('subtitle')}
        {/if}
    {if $series}
        {" . ("}{$series->getLocalizedData('title')}
            {if $publication->getLocalizedData('seriesPosition')}
                {" "}{$publication->getLocalizedData('seriesPosition')}
            {/if}
        {")"}
    {/if}
    {" . "}{$currentPress->getData('location')}{" : "}{$currentPress->getLocalizedData('name')}{"."}
{/strip}