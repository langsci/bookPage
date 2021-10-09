{function name=get_bibtex}
    {if $editors}
        {foreach from=$editors key=id item=editor}
            {if $editor@first}
                {"@book{ldelim}"}{$editor->getLocalizedFamilyName()|strip|replace:' ':''}{$publication->getData('datePublished')|date_format:"%Y"}{","}
                {"author = "}{ldelim}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" (ed.)"}
            {elseif $editor@last && $editor@total != 1}
                {"and "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" (ed.)"}
            {else}
                {"and "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" (ed.)"}
            {/if}
        {/foreach}
        {rdelim}
    {else}
        {foreach from=$authors key=id item=author}
            {if $author@first}
                {"@book{ldelim}"}{$author->getLocalizedFamilyName()|strip|replace:' ':''}{$publication->getData('datePublished')|date_format:"%Y"}{","}
                {"author = "}{ldelim}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
            {elseif $author@last && $author@total != 1}
                {"and "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
            {else}
                {"and "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
            {/if}
        {/foreach}
        {rdelim}
    {/if}

    {"title = "}{ldelim}{$publication->getLocalizedData('title')}{rdelim}{","}
    {"year = "}{ldelim}{$publication->getData('datePublished')|date_format:"%Y"}{rdelim}{","}
    {if $series}
        {"series = "}{ldelim}{$series->getLocalizedData('title')}{rdelim}{","}
        {if $publication->getLocalizedData('seriesPosition')}
            {"number = "}{ldelim}{$publication->getLocalizedData('seriesPosition')}{rdelim}{","}
        {/if}
    {/if}
    {"address = "}{ldelim}{$currentPress->getData('location')}{rdelim}{","}
    {"publisher = "}{ldelim}{$currentPress->getLocalizedData('name')}{rdelim}{rdelim}
{/function}