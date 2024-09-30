import { Module, RouterModule, HistoryModule, URI } from 'libs';

export class FollowLinkModule extends Module{
	constructor(bootstrap){
		super(bootstrap);

		this.dependencies.add(RouterModule);

        this.selector = "[data-follow-link]";

        this.onClick = this.onClick.bind(this);
	}

	async after(){
		if(this.items)
			this.items.forEach(item => item.removeEventListener('click', this.onClick, true));
		this.items = document.querySelectorAll(this.selector);
		this.items.forEach(item => item.addEventListener('click', this.onClick, true));
	}

	getParentAnchor(item){
		if(item && item.parentNode && item.tagName.toLowerCase() != "a")
			return this.getParentAnchor(item.parentNode);
		return item == document ? null : item;
	}

	getLink(e){

		if(e.currentTarget.dataset.followLink){
			return {
				url:e.currentTarget.dataset.followLink,
				target:e.currentTarget.dataset.followLinkTarget ? e.currentTarget.dataset.followLinkTarget : '_self'
			};
		}

		const anchors = [
			this.getParentAnchor(e.target),
			e.currentTarget.querySelector('a')
		];

		for(const anchor of anchors){
			if(anchor){
				const url 	 = anchor.getAttribute('href');
				const target = anchor.getAttribute('target');
				if(url) return {
					url:url,
					target:target ? target : '_self'
				};
			}
		}

		return {
			url:e.currentTarget.dataset.followLink,
			target:e.currentTarget.dataset.followLinkTarget
		};
	}

    onClick(e){

		if(e.target.tagName.toLowerCase() == 'a'){
			return;
		}
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();

		const link = this.getLink(e);

		link.target = e.metaKey || (new URI(link.url)).external ? '_blank' : link.target;

		if(link.target == "_blank")
			return window.open(link.url);

		if(this.bootstrap.modules.has(HistoryModule)){
			if(!this.bootstrap._running)
				this.bootstrap.get(RouterModule).set(link.url);
		}
		else  window.location.href = link.url;
	}
}