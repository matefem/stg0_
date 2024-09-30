import { StaticComponent } from 'libs';

export class Expertise35Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.items = Array.from(this.element.querySelectorAll('.item')).map(elem => {
			return {
				elem,
				trigger: elem.querySelector('.text'),
				inner: elem.querySelector('.inner'),
				img: elem.querySelector('.image img'),
				height: 1,
				opened: false,
				event: null
			}
		})

		this.items.forEach(item => {
			item.event = this.handleClick.bind(this, item)
		})
    }
	attach(){
		if(this.items.length == 0) return
		this.handleResize()

		this.items.forEach((item, i) => {
			this.closeItem(item, true);
			item.trigger.addEventListener('click', item.event);
		})

		this.openItem(this.items[0], true);

		this.viewport.on('resize', this.handleResize);
		this.viewport.on('media', this.handleMedia);
	}
	detach(){
		if(this.items.length == 0) return

		this.viewport.off('resize', this.handleResize)
	}

	handleResize = () => {
		if(!this.viewport.medias.desktop) return
		let last;
		this.items.forEach(item => {
			last = item.inner.style.height;
			item.inner.style.height = '';

			item.height = item.inner.getBoundingClientRect().height;

			item.inner.style.height = last;
		});
	}

	handleMedia = () => {
		if(this.viewport.medias.desktop) {
			this.items.forEach((item, i) => {
				this.closeItem(item, true);
			})

			this.openItem(this.items[0], true);
		} else {
			this.items.forEach((item, i) => {
				item.elem.classList.add('active');

				item.inner.style.height = ''
				item.img.style.opacity = ''
			})
		}
	}

	handleClick(item, e) {
		this.items.forEach(i => {
			if(i != item || item.opened) this.closeItem(i);
			else this.openItem(i);
		})
	}

	openItem(item, force=false) {
		if(!force && item.opened || !this.viewport.medias.desktop) return
		item.elem.classList.add('active');

		item.inner.style.height = item.height+'px'
		item.img.style.opacity = ''

		item.opened = true
	}

	closeItem(item, force=false) {
		if(!force && !item.opened || !this.viewport.medias.desktop) return
		item.elem.classList.remove('active');

		item.inner.style.height = '0px'
		item.img.style.opacity = '0'

		item.opened = false
		
	}
}