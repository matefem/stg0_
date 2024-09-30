import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';


import WireframeGlobe from '../../libs/WireframeGlobe.js';

export class Inter06Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.canvas = this.element.querySelector('.globe-canvas');
		this.globe = new WireframeGlobe(this.canvas);
    }

	attach(){
		this.globe.attach()
		this.viewport.on('resize', this.handleResize);

		this.tl = ScrollTrigger.create({
			trigger: this.element,
			onEnter: () => {
				this.globe.open()
			},
			onEnterBack: () => {
				this.globe.open()
			},
			onLeave: () => {
				this.globe.close()
			},
			onLeaveBack: () => {
				this.globe.close()
			}
		});
	}

	detach(){
		this.globe.detach()
		this.viewport.off('resize', this.handleResize);
	}

	handleResize = () => {
		this.globe.resize();
	}
}