import { StaticComponent } from 'libs';
import { gsap } from 'gsap';

export class Video04Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.container 	= this.element.querySelector('.video-container');
		this.video 		= this.element.querySelector('.video');
    }
	
	open(){

	}
	close(){

	}

	attach(){
		this.t = gsap.timeline({scrollTrigger: {
			trigger: this.element,
			once: true
		}});

		this.t.fromTo(this.container, {scale: 0.5}, {scale: 1, duration: 1, ease: 'power2.out'}, 0);
		this.t.fromTo(this.video, {scale: 2}, {scale: 1, duration: 1, ease: 'power2.out'}, 0);
	}
	detach(){
		this.t.kill();
	}
}