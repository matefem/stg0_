import { StaticComponent } from 'libs';
import { gsap } from 'gsap';

export class Formation22Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.resize = this.resize.bind(this);

		this.hours = Array.from(this.element.querySelectorAll('.hours')).map(elem => {
			return {
				elem,
				btn: elem.querySelector('.info'),
				arrow: elem.querySelector('.icon-arrow-bottom'),
				content: elem.querySelector('.all-dates'),
				toggle: null,
				opened: false,
				height: 1
			}
		});

		this.hours.forEach(hour => {
			hour.toggle = this.handleHoursClick.bind(this, hour)
		});
    }

	attach(){
		this.hours.forEach(hour => {
			hour.btn.addEventListener('click', hour.toggle);
		});
		this.viewport.on('resize', this.resize);
	}

	detach(){
		this.hours.forEach(hour => {
			hour.btn.removeEventListener('click', hour.toggle);
		});
		this.viewport.off('resize', this.resize);
	}

	handleHoursClick(hour, e) {
		if(hour.opened){
			hour.content.style.height = '0px';
			hour.arrow.style.transform = ''
		} else {
			hour.content.style.height = hour.height+'px';
			hour.arrow.style.transform = 'rotate(180deg)'
		}

		hour.opened = !hour.opened;
	}

	resize() {
		this.hours.forEach(hour => {
			hour.content.style.height = ''
			hour.height = hour.content.getBoundingClientRect().height;
			if(hour.opened) hour.content.style.height = hour.height+'px';
			else hour.content.style.height = '0px';
		})
	}
}