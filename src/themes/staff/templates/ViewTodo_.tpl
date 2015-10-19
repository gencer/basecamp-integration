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
<div class="basecamp_viewtodo">
	<div class="basecamp_todo">
		<div <{if $_isCompleted}>class = "basecamp_task_completed" title="<{$_titleCompleted}>"<{/if}>><{escape($_task)}></div>
		<{if !$_isCompleted && ($_dueAt || $_assignee)}>
		<div>
			<{if $_dueAt}>
			<div class="basecamp_viewtodo_right" title="<{$_tipDueDate}>">
				<img border="0" align="absmiddle" src="<{$_swiftpath}>__apps/basecamp/themes/__cp/images/due_clock_16_16.png">
				<{date_format($_dueAt, "%d/%m/%Y")}>
			</div>
			<{/if}>
			<{if $_assignee}>
			<div class="basecamp_viewtodo_right" title="<{$_tipAssigned}>">
				<img border="0" align="absmiddle" src="<{$_swiftpath}>__apps/basecamp/themes/__cp/images/person_16_16.gif">
				<{escape($_assignee)}>
			</div>
			<{/if}>
		</div>
		<div class="basecamp_clearAll"></div>
		<{/if}>
	</div>
	<{if $_comments}>
	<hr />
	<div class="basecamp_viewtodo_comment">
		<{foreach $_comments _item}>
		<div class="bubble">
			<div class="notebubble"><blockquote><p><{escape($_item.content)}></p></blockquote></div>
			<cite class="tip">
				<{if $_item.creator}><strong>By <{escape($_item.creator.name)}> <{/if}><{if $_item.created_at}>On <{date_format($_item.created_at,  "%d/%m/%Y %H:%M:%S")}></strong><{/if}>
			</cite>
		</div>
		<{/foreach}>
	</div>
	<{/if}>
</div>
<{/if}>