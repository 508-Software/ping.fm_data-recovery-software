(()=>{"use strict";const e=window.wp.element,t=window.wp.plugins,n=window.wp.editPost,d=window.wp.i18n,o=window.wp.date,i=window.wp.data,a=window.wp.compose,l=window.wp.components,r=(0,a.compose)([(0,i.withSelect)((e=>({editedModified:e("core/editor").getEditedPostAttribute("modified"),currentModified:e("core/editor").getCurrentPostAttribute("modified"),meta:e("core/editor").getEditedPostAttribute("meta")}))),(0,i.withDispatch)((e=>({handleModified(t){document.getElementById("clm-modified-date").setAttribute("value",t),e("core/editor").editPost({modified:t})}})))])((t=>{let{editedModified:n,currentModified:i,handleModified:a,meta:r}=t;const m=(0,o.__experimentalGetSettings)(),c=`${m.formats.date} ${m.formats.time}`,{_stopmodifiedupdate:s}={...r};return(0,e.createElement)(e.Fragment,null,s?(0,e.createElement)(e.Fragment,null,(0,e.createElement)("span",null,(0,d.__)("Last modified","clm-date")),(0,e.createElement)("b",null,(0,o.dateI18n)(c,i))):(0,e.createElement)(e.Fragment,null,(0,e.createElement)("span",null,(0,d.__)("Modified","clm-date")),(0,e.createElement)(l.Dropdown,{popoverProps:{placement:"bottom-start"},contentClassName:"edit-post-post-schedule__dialog",renderToggle:t=>{let{onToggle:d,isOpen:i}=t;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(l.Button,{className:"edit-post-post-schedule__toggle",onClick:d,"aria-expanded":i,variant:"tertiary"},(0,o.dateI18n)(c,n)))},renderContent:()=>(0,e.createElement)(l.DateTimePicker,{currentDate:n,onChange:e=>a(e),__nextRemoveHelpButton:!0,__nextRemoveResetButton:!0})})))})),m=(0,a.compose)([(0,i.withSelect)((e=>({meta:e("core/editor").getEditedPostAttribute("meta")}))),(0,i.withDispatch)((e=>({handleFreezeModified(t,n){const d={...t,_stopmodifiedupdate:n};e("core/editor").editPost({meta:d})}})))])((t=>{let{meta:n,handleFreezeModified:o}=t;const{_stopmodifiedupdate:i}={...n};return(0,e.createElement)(e.Fragment,null,(0,e.createElement)("span",null,(0,d.__)("Freeze modified date","clm-date")),(0,e.createElement)(l.FormToggle,{checked:i,onChange:()=>o(n,!i)}))}));!function(){const e=document.createElement("input");e.type="hidden",e.name="clm_modified",e.id="clm-modified-date",document.getElementsByClassName("metabox-location-normal")[0].appendChild(e)}(),(0,t.registerPlugin)("post-change-last-modified",{render:()=>(0,e.createElement)(e.Fragment,null,(0,e.createElement)(n.PluginPostStatusInfo,null,(0,e.createElement)(r,null)),(0,e.createElement)(n.PluginPostStatusInfo,null,(0,e.createElement)(m,null)))})})();