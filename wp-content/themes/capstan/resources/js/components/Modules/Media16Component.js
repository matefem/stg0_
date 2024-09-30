import { StaticComponent } from 'libs';
import { gsap } from 'gsap';

export class Media16Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.media = this.element.querySelector('.media')
		this.video = this.element.querySelector('.video')

		this.playing = false;
		this.ticking = false;
    }

	attach(){
		this.media.addEventListener('click', this.handleMediaCLick)
	}

	detach(){
		this.media.removeEventListener('click', this.handleMediaCLick)
	}

	handleMediaCLick = () => {
		if(this.playing) {
			this.pause()
		} else {
			this.play();
		}
	}

	play() {
		this.video.play()
		this.video.setAttribute('controls', '')
		this.playing = true
		this.media.classList.add('playing')
	}

	pause() {
		this.video.pause()
		this.video.removeAttribute('controls')

		this.playing = false
		this.media.classList.remove('playing')
	}

	tick() {
		if(!this.ticking) return;

		if(this.video.currentTime >= this.video.duration) {
			this.pause();
		}

		requestAnimationFrame(this.tick);
	}

	requestTick() {
		if(this.ticking) return;
		this.ticking = true;
		this.tick();
	}

	stopTick() {
		this.ticking = false;
	}
}