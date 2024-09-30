import {
	Module,
	PageModule,
	AjaxPageModule,
	ComponentsModule,
	ViewportModule
} from 'libs';
import CapstanLoaderModule from './CapstanLoaderModule';
import gsap from 'gsap';
import ScrollToPlugin from 'gsap/ScrollToPlugin';
import ScrollTrigger from 'gsap/ScrollTrigger';

export class TransitionModule extends Module{
	constructor(bootstrap){
		super(bootstrap);

		this.dependencies.add(PageModule);
		this.dependencies.add(AjaxPageModule);
		this.dependencies.add(ComponentsModule);
		this.dependencies.add(ViewportModule);
		this.header = document.querySelector("header");
		this.root = document.querySelector("#root");
	}

	get viewport() { return this.bootstrap.get(ViewportModule).viewport; }
	get pageModule() { return this.bootstrap.get(PageModule); };
	get ajaxPageModule() { return this.bootstrap.get(AjaxPageModule); };
	get components() { return this.bootstrap.get(ComponentsModule).components; };
	get loaderModule() { return this.bootstrap.get(CapstanLoaderModule); };

	get previousComponent(){ return this.pageModule.previous ? this.components.get(this.pageModule.previous) : null }
	get currentComponent(){ return this.pageModule.current ? this.components.get(this.pageModule.current) : null }

	async before(){

		var t = gsap.timeline();
		this.pageModule.autoRemove = false;
		return new Promise((resolve, reject) => {
			if(this.pageModule.current){
				t.addLabel('close',0);
				t.add(() => this.emit('close'),">");
				//t.to(this.root,{opacity:0, duration:0.2},0);
				t.add(this.loaderModule.showInterLoader(),0);
				t.set(document.scrollingElement, {scrollTop:0});
				if(this.currentComponent && typeof this.currentComponent.detach === "function") {
					t.add(this.currentComponent.detach(), 'close');
				}
				if(this.currentComponent && typeof this.currentComponent.close === "function") {
					t.add(this.currentComponent.close(), 'close');
				}
				ScrollTrigger.getAll().forEach(st => {
					if(st.vars.type && st.vars.type == "permanent") return;
					st.kill();
			   })
				t.add(resolve);

			}else{
				t.set(document.scrollingElement, {scrollTop:0},0.1);
				t.add(resolve);
			}
		});
	}

	async after(){
		this.timeline = gsap.timeline();
		if(this.pageModule.previous){
			this.timeline.add(() => {
				this.pageModule.removeElement(this.pageModule.previous);
				this.components.clean();

			},0);
			//this.timeline.to(this.root,{opacity:1, duration:0.2});
			this.timeline.add(()=>{this.loaderModule.hideInterLoader()});
			this.timeline.addLabel('open','<');
			this.timeline.add(() => {this.emit('open')}, '<');

			if(this.currentComponent && typeof this.currentComponent.open === "function") this.timeline.add(this.currentComponent.open(),'open');



		}else{
			if(this.currentComponent && typeof this.currentComponent.open === "function") this.timeline.add(this.currentComponent.open(),0);

		}
	}

	preventScroll(e) {
		e.stopPropagation();
		e.preventDefault();
	}
}