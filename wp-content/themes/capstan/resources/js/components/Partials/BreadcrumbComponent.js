import { StaticComponent } from 'libs';

export class BreadcrumbComponent extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.module = document.querySelector('.module');
    }

	attach(){
		this.bootstrap.loader.on('changed', this.handleLoaderChanged)
	}

	detach(){

	}

	handleLoaderChanged = () => {
		if(this.module && this.module.classList.contains('module-41')) {
			this.element.classList.add('override')
		} else {
			if(this.element.classList.contains('override'))
				ifthis.element.classList.remove('override')
		}
	}
}