import {
	Module,
	RouterModule,
	StateModule
} from 'libs';

import { URI } from 'libs';

class HistoryLinkModule extends Module{
	constructor(bootstrap){
		super(bootstrap);

		this.dependencies.add(StateModule);
		this.dependencies.add(RouterModule);

		this.selector = 'a:not(.no-history):not([target="_blank"])';

		this.linkClicked 	= this.linkClicked.bind(this);

		this.enabled = true;
	}

	get priority(){ return 10; }

	get enabled(){ return this._enabled; }
	set enabled(enabled){
		if(this._enabled != enabled){
			this._enabled = enabled;
			if(enabled && this.links){
				this.links.forEach(link => link.removeEventListener('click', this.linkClicked));
			}
		}
	}

	linkClicked(e){
		let url = e.currentTarget.getAttribute('href').split('#')[0];
		const prefix = url.split(':')[0]

		if(prefix != 'tel' && prefix != 'mailto' && (e.currentTarget.getAttribute('download') == null || e.currentTarget.getAttribute('download') == '') && e.currentTarget.getAttribute('data-method') != "noajax") {
			let uri = new URI(url);
			if(!uri.external){
				e.preventDefault();
				this.bootstrap.get(RouterModule).set(url);
			}
		}
	}


	after(){
		if(this.links)
			this.links.forEach(link => link.removeEventListener('click', this.linkClicked));
		if(this._enabled){
			this.links = document.querySelectorAll(this.selector);
			if(this.links)
				this.links.forEach(link => link.addEventListener('click', this.linkClicked));
		}
	}
}

export default HistoryLinkModule;
export { HistoryLinkModule };