import {
    Bootstrap,  
    HistoryModule,
    RouterModule,
    AjaxPageModule,
    ImageLoaderModule,
    ComponentsModule,
    StateModule,
    ViewportModule
} from 'libs';


import {
	AnimatorClassModule,
	TransitionModule,
	FollowLinkModule,
	CapstanLoaderModule,
    HistoryLinkModule
} from '../modules';

export class CapstanBootstrap extends Bootstrap{
	constructor(){
		super(
		    HistoryModule,
		    RouterModule,
		    AjaxPageModule,
		    ImageLoaderModule,
		    ComponentsModule,
		    StateModule,
		    ViewportModule,
		    AnimatorClassModule,
		    TransitionModule,
		    FollowLinkModule,
		    CapstanLoaderModule,
		    HistoryLinkModule,
		);
	}

	get components(){
		return this.get(ComponentsModule).components;
	}
	get viewport(){
		return this.get(ViewportModule).viewport;
	}
	get router(){
		return this.get(RouterModule).router;
	}
	get loader(){
		return this.get(CapstanLoaderModule);
	}
}