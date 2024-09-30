import {
	Scene,
	WebGLRenderer,
	PerspectiveCamera,
	Mesh,
	Object3D,
	TorusGeometry,
	Color,
	MeshBasicMaterial
} from 'three'

export default class WireframeGlobe {
	constructor($canvas) {
		this.render 		= this.render.bind(this)
		this.resize 		= this.resize.bind(this)
		this.tick 			= this.tick.bind(this)
		this.requestTick 	= this.requestTick.bind(this)
		this.stopTick 		= this.stopTick.bind(this)

		this.$canvas 	= $canvas;

		this.rotation 	= 0;
		this.ticker 	= false;
	}

	attach() {
		this.scene = new Scene();
		this.scene.background = new Color(0xffffff);
		this.renderer = new WebGLRenderer({antialias: true, canvas: this.$canvas});
		this.renderer.setPixelRatio(window.devicePixelRatio);

		this.size = this.$canvas.getBoundingClientRect();
		this.renderer.setSize(this.size.width, this.size.height);
		this.renderer.clearColor(0xffffff);

		this.camera = new PerspectiveCamera( 30, 1, 1, 10000 );
		this.camera.position.set(0, 0, 10);

		this.globe = new Object3D();

		this.material = new MeshBasicMaterial({color: 0xeeeeee })
		let mesh;
		for(let i = 0;i < 12;i++) {
			mesh = new Mesh(
				new TorusGeometry( 2, 0.005, 4, 50 ),
				this.material
			);
			mesh.rotation.y = (i / 12) * Math.PI * 2

			this.globe.add(mesh)
		}

		for(let i = 0;i < 6;i++) {
			const radius = 2.14 - Math.abs(Math.asin(1 - (i + 0.5) / 3))
			mesh = new Mesh(
				new TorusGeometry(radius, 0.005, 4, 50 ),
				this.material
			);
			mesh.rotation.x = Math.PI * 0.5
			mesh.position.y = 2 - ((i + 0.5) / 6) * 4

			this.globe.add(mesh)
		}

		this.container = new Object3D()
		this.container.add(this.globe);
		this.scene.add(this.container);

		this.t = 0;
	}

	detach() {
		if(this.opened) this.close();
	}

	open() {
		if(this.opened) return

		this.resize()
		this.requestTick();
		this.opened = true;

		window.addEventListener('mousemove', this.handleMove);
	}

	close() {
		if(!this.opened) return

		this.stopTick();
		this.opened = false;

		window.removeEventListener('mousemove', this.handleMove);
	}

	render() {
		this.renderer.render(this.scene, this.camera);
	}

	tick() {
		if(!this.ticker) return
		this.globe.rotation.y += 0.002
		requestAnimationFrame(this.tick)
		this.render()
	}

	requestTick() {
		this.ticker = true;
		this.tick();
	}

	stopTick() {
		this.ticker = false;
	}

	handleMove = (e) => {
		this.container.rotation.x = (e.clientY / this.size.height - 0.5) * 0.2
		this.container.rotation.y = (e.clientX / this.size.width - 0.5) * 0.2
	}

	resize() {
		this.size = this.$canvas.getBoundingClientRect();
		this.camera.aspect = this.size.width / this.size.height;
		this.camera.updateProjectionMatrix();

		this.renderer.setSize(this.size.width, this.size.height);
	}
}