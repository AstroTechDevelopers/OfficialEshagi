"use strict";(self.__LOADABLE_LOADED_CHUNKS__=self.__LOADABLE_LOADED_CHUNKS__||[]).push([[7837],{32999:function(e,t,n){n.r(t),n.d(t,{default:function(){return K}});var l=n(67294),o=n(69931),i=n.n(o),r=n(72883),a=n(86237),s=n(10125),d=n(18872),c=n(98225),m=n(66706),u=n(81598),p=n(34163),g=n(1072),b=n(63293),C=n(8545),v=n(72356),S=n(2517),E=n(87332),T=n(79596),f=n(84894),h=n(30976),y=n(38602),N=n(93948),I=n(45697),x=n.n(I),A=n(94402);const R=Object.freeze({HORIZONTAL:"Horizontal",VERTICAL:"Vertical"}),k=x().arrayOf(x().shape({id:x().string.isRequired,title:x().string.isRequired,button:x().shape({}),description:x().objectOf(x().string),descriptionRichText:x().shape({}),eyebrow:x().string,image:x().shape({gatsbyImageData:A.sW}),titleColor:x().string}));x().arrayOf(x().shape({contentCardAlignment:x().oneOf(Object.values(R)).isRequired,contentCards:k.isRequired,id:x().string.isRequired,title:x().string.isRequired,description:x().objectOf(x().string),tabText:x().string,videoId:x().string,videoPosterImage:x().shape({file:x().shape({contentType:x().string,fileName:x().string,url:x().string})})}));var L="TabContent-button--4VE6e",O="TabContent-card--79gGY";const w=e=>{let{background:t,title:n,description:o,contentCards:r,videoPosterImage:a,videoId:s,theme:d,contentCardAlignment:m,extraClassName:u}=e;const p=(0,l.useRef)(null),g=r.filter((e=>null!==e.image)),b=m===R.VERTICAL,C=b?r.filter((e=>null===e.image)):r.slice(0,4),E=C.length>0,I=i()(b?"TabContent-featured--A0CMM":O,{"TabContent-featuredPadding--47p34":(0,c.H)(t)}),x=i()("TabContent-title--2XAkK",f.h2),A=i()("TabContent-description--4hxZZ",f.d1),k=i()(b?"TabContent-featuredCard--6OjY6":"TabContent-video--6Uy0q",u),w=i()(f.ZS,"TabContent-tabDescription--5sFnN"),P=i()("TabContent-smallCards--458Xs",{"TabContent-twoSmallCards--4eKJi":r.length>2&&2===C.length,"TabContent-twoCards--DGARo":2===r.length}),H=e=>l.createElement(v.Z,{image:e,objectFit:"cover",objectPosition:"50% 50%",alt:e.description});return l.createElement("section",{ref:p,role:"tabpanel",className:k},l.createElement(h.Z,{title:n,description:b?null==o?void 0:o.description:null,theme:d,extraClassName:"TabContent-header--4Zfwu",extraTitleClassName:f.h1,extraDescriptionClassName:w}),!b&&s&&l.createElement("div",{className:"TabContent-figure--3loOl"},l.createElement(T.Z,{videoId:s,videoPoster:a})),l.createElement("div",{className:"TabContent-cards--3VyOl"},b&&g.map((e=>{var t;return l.createElement("div",{className:I,key:e.id},e.image&&l.createElement("div",{className:"TabContent-image--1SBYi",style:{maxWidth:e.imageWidth}},e.mobileImage?l.createElement(l.Fragment,null,l.createElement(N.rp,null,H(e.mobileImage)),l.createElement(N.hd,null,H(e.image))):H(e.image)),l.createElement("div",null,e.eyebrow&&l.createElement(y.default,{extraClassName:f.VJ,body:e.eyebrow}),e.title&&l.createElement("h2",{className:x,style:{color:(0,c.H)(e.titleColor)}},e.title),e.description&&l.createElement("p",{className:A},e.description.description),e.descriptionRichText&&l.createElement(y.default,{extraClassName:A,body:e.descriptionRichText}),e.button&&l.createElement(S.Z,{extraClassName:L,text:e.button.text,link:(null===(t=e.button.longURL)||void 0===t?void 0:t.longURL)||e.button.url,stageUrl:e.button.stageUrl})))})),E&&l.createElement("div",{className:P},C.map((e=>{var t;return l.createElement("div",{key:e.id,className:O},e.eyebrow&&l.createElement(y.default,{extraClassName:"TabContent-eyebrow--21hxN",body:e.eyebrow}),e.title&&l.createElement("h2",{className:x,style:{color:(0,c.H)(e.titleColor)}},e.title),e.description&&l.createElement("p",{className:A},e.description.description),e.descriptionRichText&&l.createElement(y.default,{extraClassName:A,body:e.descriptionRichText}),e.button&&l.createElement(S.Z,{extraClassName:L,text:e.button.text,link:(null===(t=e.button.longURL)||void 0===t?void 0:t.longURL)||e.button.url,stageUrl:e.button.stageUrl}))})))))};w.defaultProps={background:null,description:null,videoId:null,videoPosterImage:null,extraClassName:null};var P=w;const H=e=>{let{label:t,isActive:n,onClick:o}=e;const r=i()("Tabs-heading--6ZmWd",f.jT,{"Tabs-active--4G204":n});return l.createElement("button",{type:"button",className:r,tabIndex:"0",onClick:o,onKeyPress:e=>{let{key:t}=e;"Enter"===t&&o()},role:"tab","aria-selected":n},t)},_=e=>{let{background:t,tabs:n,theme:o}=e;const{0:r,1:a}=(0,l.useState)(0),{0:s,1:d}=(0,l.useState)(!0),c=i()({"Tabs-fadeIn--6RKez":s});return l.createElement(l.Fragment,null,n.length>1&&l.createElement("div",{className:"Tabs-tabScrollContainer--10dEz"},l.createElement("div",{role:"tablist",className:"Tabs-tabs--5yVkr"},n.map(((e,t)=>l.createElement(H,{key:e.tabText,label:e.tabText,isActive:t===r,onClick:()=>{return e=t,d(!1),void setTimeout((()=>{d(!0),a(e)}),100);var e}}))))),l.createElement(P,Object.assign({},n[r],{theme:o,background:t,extraClassName:c})))};_.defaultProps={background:null};var U=_;const B=e=>{let{background:t,buttons:n,tabs:o}=e;const i=(0,c.L)(t,m.yU.DARK);return l.createElement(l.Fragment,null,l.createElement(U,{tabs:o,theme:i,background:t}),n&&l.createElement("div",{className:"TabControl-buttons--6uQwx"},n.map((e=>{let{text:t,url:n,longURL:o,stageUrl:r}=e;return l.createElement(S.Z,{key:n,text:t,link:(null==o?void 0:o.longURL)||n,stageUrl:r,inverted:i===m.yU.DARK})}))))};B.defaultProps={buttons:null};var z=B;var W=n(77374),D=n(4327),Z="SimpleContent-content--49xAR";const j=e=>{var t,n,o,E,h,y,N,I,x,A;let{appStoreBadges:R,backgroundPadding:k,backgroundWidth:L,buttons:O,borderColor:w,containedBackground:P,contentful_id:H,description:_,descriptionWidth:U,descriptionSize:B,descriptionColor:j,enteredOnce:K,eyebrow:F,eyebrowColor:G,formattedTitle:M,image:V,imageBottom:Y,isLeftAligned:q,logos:J,seoTitleTag:X,tabControls:$,noAppear:Q,onRef:ee,videoId:te,videoPoster:ne,titleSize:le,titleColor:oe,rawScript:ie,...re}=e;const ae=(0,r.pc)({appStoreBadges:R,backgroundPadding:k,backgroundWidth:L,buttons:O,borderColor:w,containedBackground:P,description:_,descriptionWidth:U,descriptionSize:B,descriptionColor:j,enteredOnce:K,eyebrow:F,eyebrowColor:G,formattedTitle:M,image:V,imageBottom:Y,isLeftAligned:q,logos:J,seoTitleTag:X,tabControls:$,noAppear:Q,onRef:ee,videoId:te,videoPoster:ne,titleSize:le,titleColor:oe,rawScript:ie,...re,sys:{id:H}}),se=ae.seoTitleTag||"h2",de=(0,c.H)(ae.borderColor),ce=de?{border:`3px solid ${de}`}:{},me=(0,c.H)(P),ue={backgroundColor:ae.background?ae.background.color:me},pe=i()({[D[`spacingTop${null===(t=ae.backgroundPadding)||void 0===t?void 0:t.top}`]||D.spacingTopLarge]:me,[D[`spacingBottom${null===(n=ae.backgroundPadding)||void 0===n?void 0:n.bottom}`]||D.spacingBottomLarge]:me}),ge=i()({"SimpleContent-container--5lpoO":!!de,"SimpleContent-backgroundWidthFull--2g976":ae.backgroundWidth===m.lI.FULL_WIDTH}),be=i()("SimpleContent-simpleContent--6PMcH",{"SimpleContent-leftAligned--2fpEp":ae.isLeftAligned,"SimpleContent-imageBottom--3Aonl":ae.imageBottom,"SimpleContent-hasBorder--72fzy":!!de,enteredOnce:K,noAppear:Q}),Ce=i()("SimpleContent-eyebrow--2ZrgZ",f.jT),ve=i()("SimpleContent-title--NCjVf",{[f.SG]:ae.titleSize===m.H3.DISPLAY_1,[f.d4]:ae.titleSize===m.H3.DISPLAY_2,[f.h1]:ae.titleSize===m.H3.H1||null===ae.titleSize,[f.h2]:ae.titleSize===m.H3.H2,[f.h3]:ae.titleSize===m.H3.H3,"SimpleContent-withEyebrow--229Ii":ae.eyebrow,"SimpleContent-noMargin--5YNO4":!ae.description,[f.nP]:ae.titleSize===m.H3.HEADING}),Se=i()("SimpleContent-description--16REx",{[f.d1]:!ae.descriptionSize||ae.descriptionSize===m.H3.BODY_REGULAR,[f.ZS]:ae.descriptionSize===m.H3.BODY_LARGE,[f.VJ]:ae.descriptionSize===m.H3.CAPTION,"SimpleContent-lightText--48bn6":!1===ae.descriptionColor,"SimpleContent-width_800--5gvxZ":ae.descriptionWidth===m.fl.WIDTH_800||null===ae.descriptionWidth,"SimpleContent-width_960--34dXw":ae.descriptionWidth===m.fl.WIDTH_960,"SimpleContent-width_1016--73hOb":ae.descriptionWidth===m.fl.WIDTH_1016,"SimpleContent-width_1224--1Ht9c":ae.descriptionWidth===m.fl.WIDTH_1224}),Ee={renderMark:{[s.MARKS.BOLD]:e=>l.createElement("span",{className:f.Se},e)},renderNode:{[s.BLOCKS.PARAGRAPH]:(e,t)=>l.createElement("p",{className:"SimpleContent-paragraph--3Ne91"},t)},renderText:e=>(0,u.cW)(e)},Te={renderMark:{[s.MARKS.ITALIC]:e=>l.createElement("span",{className:f.pU},e)},renderNode:{[s.BLOCKS.PARAGRAPH]:(e,t)=>l.createElement(l.Fragment,null,t)},renderText:e=>(0,u.cW)(e)},fe=ae.videoId||ae.image,he=fe?l.createElement("div",{className:"SimpleContent-figure--7OhKO"},ae.videoId?l.createElement(T.Z,{videoId:ae.videoId,videoPoster:null!==(o=ae.videoPoster)&&void 0!==o&&o.url?{file:{url:ae.videoPoster.url}}:ae.videoPoster}):l.createElement(v.Z,{alt:ae.image.description||"",image:ae.image,src:ae.image?ae.image.url:null===(E=V.file)||void 0===E?void 0:E.url})):null,ye=(null===(h=ae.buttonsCollection)||void 0===h?void 0:h.items.length)>0?ae.buttonsCollection.items:O,Ne=(null===(y=ae.appStoreBadgesCollection)||void 0===y?void 0:y.items.length)>0?ae.appStoreBadgesCollection.items:R;var Ie,xe;null!==(N=ae.description)&&void 0!==N&&N.json?ae.description.raw=JSON.stringify(null===(Ie=ae.description)||void 0===Ie?void 0:Ie.json):null===(null===(I=ae.description)||void 0===I?void 0:I.json)&&(ae.description=null);null!==(x=ae.title)&&void 0!==x&&x.json?ae.formattedTitle={raw:JSON.stringify(null===(xe=ae.title)||void 0===xe?void 0:xe.json)}:null===(null===(A=ae.title)||void 0===A?void 0:A.json)&&(ae.formattedTitle=null);return l.createElement("div",Object.assign({style:ue,id:"CONTAINER",className:pe},a.z.getProps({entryId:H,fieldId:"simpleContent"})),l.createElement(C.Z,{style:ce,extraClassName:ge},l.createElement("div",{ref:ee,className:be},fe&&!ae.imageBottom&&he,ae.formattedTitle&&l.createElement("div",{className:"SimpleContent-titleContainer--7Lr5d"},ae.eyebrow&&l.createElement("div",Object.assign({className:Ce,style:{color:(0,c.H)(ae.eyebrowColor)}},a.z.getProps({entryId:H,fieldId:"eyebrow"})),ae.eyebrow),l.createElement(se,Object.assign({className:ve,style:{color:(0,c.H)(ae.titleColor)}},a.z.getProps({entryId:H,fieldId:"title"})),(0,d.Z)(ae.formattedTitle,Te))),ae.description&&l.createElement("div",Object.assign({className:Se},a.z.getProps({entryId:H,fieldId:"description"})),(0,d.Z)(ae.description,Ee)),ae.rawScript&&l.createElement("div",{dangerouslySetInnerHTML:{__html:ae.rawScript?ae.rawScript.rawScript:ie.rawScript}}),$&&l.createElement(z,{tabs:$,buttons:O,background:P,className:Z}),!$&&ye&&l.createElement("div",{className:"SimpleContent-buttons--2a1b0"},ye.map(((e,t)=>{var n;return l.createElement(p.Experience,{key:e.text,text:e.text,link:(null===(n=e.longURL)||void 0===n?void 0:n.longURL)||e.url,stageUrl:e.stageUrl,extraClassName:"SimpleContent-button--RbEjO",intent:t>0?"tertiary":"primary",component:S.Z,experiences:(0,g.u)(e.experiences),id:e.contentful_id})}))),Ne&&l.createElement("div",Object.assign({className:Z},a.z.getProps({entryId:H,fieldId:"appStoreBadges"})),l.createElement(b.Z,{appStoreUrl:Ne[0].appStoreUrl,googlePlayUrl:Ne[0].googlePlayUrl,largeBadges:!0})),fe&&ae.imageBottom&&he),J&&l.createElement("div",{className:"SimpleContent-logosSection--2KpLa"},J.map(((e,t)=>l.createElement(l.Fragment,{key:e.id||t},l.createElement(W.default,e)))))))};j.defaultProps={appStoreBadges:null,backgroundPadding:m.v0.LARGE,backgroundWidth:m.lI.FULL_WIDTH,buttons:null,description:null,descriptionWidth:m.fl.WIDTH_800,eyebrow:null,formattedTitle:null,image:null,imageBottom:null,isLeftAligned:!1,logos:null,rawScript:null,seoTitleTag:"h2",tabControls:null,videoId:null,videoPoster:null,titleSize:null,containedBackground:{color:null,customColor:null},descriptionSize:null,descriptionColor:null,borderColor:{color:null,customColor:null},titleColor:{color:null,customColor:null},eyebrowColor:{color:null,customColor:null}};var K=(0,E.Z)()(j)}}]);