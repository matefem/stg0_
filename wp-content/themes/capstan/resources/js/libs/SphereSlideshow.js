export default class SphereSlideshow {
	constructor($canvas, urls) {
		this.render 		= this.render.bind(this)
		this.resize 		= this.resize.bind(this)
		this.tick 			= this.tick.bind(this)
		this.requestTick 	= this.requestTick.bind(this)
		this.open 			= this.open.bind(this)
		this.close 			= this.close.bind(this)

		this.$canvas 	= $canvas;
		this.urls 		= urls;
		this.images 	= [];
		this.ratios 	= [];
		this.textures 	= [];

		this.opened 	= false;

		this._progress 	= 0;
		this._opacity 	= 1;

		this._target	= 0;

		this.ticker 	= false;
	}

	attach() {
		this.gl = this.$canvas.getContext("webgl");

		if (!this.gl) return;

		if(!this.initShaderProgram()) return;
		this.initPositionBuffer();
		this.initUniforms();

		this.t = 0;

		this.loadImages();
	}

	detach() {
		if(this.opened) this.close();
	}

	open() {
		if(this.opened) return

		this.resize()
		this.requestTick();
		this.opened = true;
	}

	close() {
		if(!this.opened) return

		this.stopTick();
		this.opened = false;
	}


	loadShader(type, src) {
  		const shader = this.gl.createShader(type);

		this.gl.shaderSource(shader, src);
		this.gl.compileShader(shader);

		if (!this.gl.getShaderParameter(shader, this.gl.COMPILE_STATUS)) return null;
		return shader;
	}

	initShaderProgram() {
		this.vertexShader 	= this.loadShader(this.gl.VERTEX_SHADER,   this.vs());
		this.fragmentShader = this.loadShader(this.gl.FRAGMENT_SHADER, this.fs());

		this.program		= this.gl.createProgram();

		this.gl.attachShader(this.program, this.vertexShader);
		this.gl.attachShader(this.program, this.fragmentShader);

		this.gl.linkProgram(this.program);

		if (!this.gl.getProgramParameter(this.program, this.gl.LINK_STATUS)) return null;
		return true
	}
	initPositionBuffer() {
		this.positionBuffer = this.gl.createBuffer();
		this.gl.bindBuffer(this.gl.ARRAY_BUFFER, this.positionBuffer);

		const positions = [
			 1.0,  1.0,
			-1.0,  1.0,
			 1.0, -1.0,
			-1.0, -1.0,
		];

		this.gl.bufferData(this.gl.ARRAY_BUFFER, new Float32Array(positions), this.gl.STATIC_DRAW);
	}
	initUniforms() {
		this.uniforms = {
			ratio: 		this.gl.getUniformLocation(this.program, "ratio"),
			progress: 	this.gl.getUniformLocation(this.program, "progress"),
			opacity: 	this.gl.getUniformLocation(this.program, "opacity"),
			images: 	[],
			ratios: 	[]
		}

		this.urls.forEach((u, i) => {
			this.uniforms.images[i] = this.gl.getUniformLocation(this.program, "image"+i)
			this.uniforms.ratios[i] = this.gl.getUniformLocation(this.program, "ratio"+i)
		})

		this.opacity = 1
	}

	loadImages() {
		this.urls.forEach((url, i) => {
			const img = new Image();
			img.src = url;
			img.addEventListener('load', () => {
				this.images[i] = img
	 			if(this.images.filter(i => !!i).length == this.urls.length)
	 				this.imageLoaded()
	 		});
		})
	}

	imageLoaded() {
		this.images.reverse().forEach((img, i) => {
			const texture = this.gl.createTexture();
			this.gl.bindTexture(this.gl.TEXTURE_2D, texture);

			this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_WRAP_S, this.gl.CLAMP_TO_EDGE);
		 	this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_WRAP_T, this.gl.CLAMP_TO_EDGE);
		 	this.gl.texParameteri(this.gl.TEXTURE_2D, this.gl.TEXTURE_MIN_FILTER, this.gl.LINEAR);

			this.gl.texImage2D(this.gl.TEXTURE_2D, 0, this.gl.RGBA, this.gl.RGBA, this.gl.UNSIGNED_BYTE, img);

		 	this.textures[i] = texture;
		});

		this.draw();
	}

	draw() {
		this.vertexPosition = this.gl.getAttribLocation(this.program, 'aVertexPosition');

		this.gl.bindBuffer(this.gl.ARRAY_BUFFER, this.positionBuffer);
		this.gl.vertexAttribPointer( this.vertexPosition, 2, this.gl.FLOAT, false, 0, 0);
		this.gl.enableVertexAttribArray(this.vertexPosition);


  		this.gl.useProgram(this.program);

		this.images.forEach((img, i) => {
		 	this.gl.uniform1i(this.uniforms.images[i], i);
		 	this.gl.uniform1f(this.uniforms.ratios[i], img.naturalWidth / img.naturalHeight);

			this.gl.activeTexture(this.gl.TEXTURE0+i);
			this.gl.bindTexture(this.gl.TEXTURE_2D, this.textures[i]);
		});

		this.resize();
		this.tick();
	}

	render() {
		this.gl.uniform1f(this.uniforms.opacity, this._opacity);
		this.gl.uniform1f(this.uniforms.progress, this._progress);

		this.gl.clearColor(0.0, 0.0, 0.0, 1.0);
		this.gl.clear(this.gl.COLOR_BUFFER_BIT|this.gl.DEPTH_BUFFER_BIT);
    	this.gl.drawArrays(this.gl.TRIANGLE_STRIP, 0, 4);
    	this.rendering = false;
	}

	requestRender() {
		this.rendering = true;
	}

	stopRender() {
		this.rendering = false;
	}

	tick() {
		requestAnimationFrame(this.tick)

		if(Math.abs(this._target - this._progress) > 0.001) {
			this._progress += (this._target - this._progress) * 0.05;
			this.requestRender();
		}

		if(this.rendering) this.render()
	}

	requestTick() {
		this.ticker = true;
		this.tick();
	}

	stopTick() {
		this.ticker = false;
	}

	resize() {
		const bbox = this.$canvas.getBoundingClientRect();
		this.width = bbox.width;
		this.height = bbox.height;
		this.$canvas.width 	= bbox.width;
		this.$canvas.height = bbox.height;
   		this.gl.viewport(0, 0, this.$canvas.width, this.$canvas.height);
		this.gl.uniform1f(this.uniforms.ratio, this.width / this.height);

		this.requestRender();
	}

	set progress(val) {
		if(val == this._progress) return
		this._progress 	= val
		this.target 	= val
		if(this.uniforms) {
			this.gl.uniform1f(this.uniforms.progress, val);
			this.requestRender();
		}
	}
	get progress() {
		return this._progress;
	}

	set target(val) {
		this._target = val
	}
	get target() {
		return this._target;
	}

	set opacity(val) {
		if(val == this._opacity) return
		this._opacity = val
		if(this.uniforms) {
			this.requestRender();
		}
	}
	get opacity() {
		return this._opacity;
	}

	vs() {
		return `
precision highp float;

attribute vec4 aVertexPosition;

varying vec2 vUv;

void main() {
	vUv = aVertexPosition.xy;
	gl_Position = aVertexPosition;
}`;
	}
	fs() {
		return `
precision highp float;

varying vec2 vUv;

#define N_IMG ${this.urls.length}
#define F_IMG ${this.urls.length}.0

uniform float ratio;
uniform float progress;
uniform float opacity;

`+ this.urls.map((u, i) => `uniform sampler2D image${i};`).join('\n')
 +`
 `+ this.urls.map((u, i) => `uniform float ratio${i};`).join('\n')
 +`

const float PI = 3.14159265359;

void main() {
	vec2 deformed = vUv;
	deformed.x *= (vUv.y * vUv.y * 0.7 + 4.0);
	deformed.y *= (0.5 + (1.0 - cos(vUv.x * PI * 0.3)) * 0.5) * 1.5;

	vec2 iUv = vec2(
		smoothstep(0.4, 0.4, cos(deformed.x * 0.5) * 2.0),
		smoothstep(0.3, 0.3, abs(cos(deformed.y / ratio * 4.0 + (F_IMG - progress - 1.0) * PI) * 2.0))
	);

	float ySlide = (deformed.y / ratio * 4.0 + (F_IMG - progress - 1.0) * PI) / PI + 0.5;
	float n = floor(ySlide);
	float debug = iUv.x * iUv.y;

	iUv.x *= clamp(deformed.x / 6.0 + 0.5, 0.0, 1.0);
	iUv.y *= clamp(fract(1.0 - ySlide), 0.0, 1.0);

	float borders = smoothstep(0.05, 0.055, iUv.y) * smoothstep(0.95, 0.945, iUv.y) * smoothstep(0.05, 0.055, iUv.x) * smoothstep(0.95, .945, iUv.x);

	vec4 color = vec4(0.0);

`+ this.urls.map((u, i) => `	color += texture2D(image${i}, (vec2((iUv.x - 0.5) / (ratio${i} / ratio) + 0.5, iUv.y) - 0.5) * min(1.0, (ratio${i} / ratio)) + 0.5) * float(n == ${i}.0);`).join('\n')
	+`

	gl_FragColor = color * debug * 0.8 * borders;
}`;
	}
}