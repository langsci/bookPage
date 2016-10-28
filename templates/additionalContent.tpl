{**
 * templates/frontend/objects/monograph_full.tpl
 *
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @brief additional content for the book page 
 *
 *}
 
{* langsci reviews *}
{if $reviewsBySubmission}
	<div class="item langsci_review">
	
		<h3 class="label">{translate key="plugins.generic.bookPage.reviews"}</h3>
		
		{* get all reviews *}
		{foreach from=$reviewsBySubmission item=review}
			<ul>
				<li>
					<a href="{$review.link}">
						{$review.name}
					</a>
					{if $review.reviewer}
						by {$review.reviewer}
					{/if}
					{if $review.date}
						published {$review.date}
					{/if}
					{if $review.money_quote}
						<div class="moneyquote">{$review.money_quote}</div>
					{/if}
				</li>
			</ul>
		{/foreach}		
	</div>
{/if}

{* langsci statistics *}
{if $statImageExists}
	<div class="item langsci_statistics">
		<h3 class="label">{translate key="plugins.generic.bookPage.statistics"}</h3>
		<div class="value">
			<a href="{$imagePath}{$publishedMonograph->getId()}{'.png'}">
				<img class="pkp_helpers_container_center" alt="{$publishedMonograph->getLocalizedFullTitle()|escape}" src="{$imagePath}{$publishedMonograph->getId()}{'.png'}" width="100%" />
			</a>
		</div>	
	</div>
{/if}