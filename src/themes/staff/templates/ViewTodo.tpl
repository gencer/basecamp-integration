<div id ="basecamp_todo_view">
<{if $_error}>
	<div class="dialogcontainer">
		<div class="dialogerror">
		</div>
		<div class="dialogerrorcontainer">
			<div class="dialogtitle"><{$_errorHeader}></div>
			<div class="dialogtext"><{$_error}></div>
		</div>
	</div>
<{/if}>
<{if $_task}>
<div class="basecamp_viewtodo_title_wrapper">
	<div class = "basecamp_viewtodo_title " <{if $_isCompleted}>style = "text-decoration: line-through" title="<{$_titleCompleted}>"<{/if}>><{escape($_task)}></div>
</div>
<div class="basecamp_viewtodo_content_wrapper">
	<div class="basecamp_viewtodo_content_wrapper">
	<{if !$_isCompleted && ($_dueAt || $_assignee)}>
	<div class="basecamp_viewtodo_content_right_wrapper">
		<{if $_dueAt}>
		<div class="basecamp_viewtodo_content_duedate"  title="<{$_tipDueDate}>">
			<img border="0" align="absmiddle" src="<{$_swiftpath}>__apps/basecamp/themes/__cp/images/due_clock_16_16.png">
			<{date_format($_dueAt, "%d/%m/%Y")}>
		</div>
		<{/if}>
		<{if $_assignee}>
		<div class="basecamp_viewtodo_content_duedate"  title="<{$_tipAssigned}>">
			<img border="0" align="absmiddle" src="<{$_swiftpath}>__apps/basecamp/themes/__cp/images/person_16_16.gif">
			<{escape($_assignee)}>
		</div>
		<{/if}>
	</div>
	<div class="basecamp_clearAll"></div>
	</div>
	<{/if}>
	<{if $_comments}>
	<{foreach $_comments _item}>
	<div class="basecamp_viewtodo_content_wrapper" style="height:8px"></div>
	<div class="basecamp_viewtodo_content_wrapper">
	<div class="basecamp_viewtodo_comment_wrapper">
		<div class="basecamp_viewtodo_poster_wrapper">
			<div class="basecamp_viewtodo_poster"><{if $_item.creator}><{escape($_item.creator.name)}> <{/if}></div>
		</div>
		<div class="basecamp_viewtodo_postedon1">
			<div class="basecamp_viewtodo_postedon2">
				<div class= "basecamp_viewtodo_postedon3"><{if $_item.created_at}><{date_format($_item.created_at,  "%d/%m/%Y %H:%M:%S")}><{/if}></div>
				<span class = "basecamp_viewtodo_postedon4" style="background: url('<{$_swiftpath}>__swift/themes/__cp/images/ticketdatefold.png') no-repeat scroll center center transparent;"></span>
			</div>
			<div class = "basecamp_viewtodo_comment1">
				<div class="basecamp_viewtodo_comment2">
					<div class="basecamp_viewtodo_comment3"><{$_item.content}></div>
				</div>
			</div>
		</div>
		<div class="basecamp_clearAll"></div>
	</div>
	</div>
	<{/foreach}>
	<div class="basecamp_viewtodo_content_wrapper" style="height:8px"></div>
	<{/if}>
</div>
<{/if}>
</div>
