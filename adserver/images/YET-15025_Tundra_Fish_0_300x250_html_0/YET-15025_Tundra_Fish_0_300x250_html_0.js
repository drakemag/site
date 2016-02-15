(function (lib, img, cjs, ss) {

var p; // shortcut to reference prototypes

// library properties:
lib.properties = {
	width: 300,
	height: 250,
	fps: 24,
	color: "#000000",
	manifest: [
		{src:"images/bult.png", id:"bult"},
		{src:"images/Group2.png", id:"Group2"},
		{src:"images/Group2copy2.png", id:"Group2copy2"},
		{src:"images/Group4.png", id:"Group4"},
		{src:"images/Layer3.jpg", id:"Layer3"}
	]
};



// symbols:



(lib.bult = function() {
	this.initialize(img.bult);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,263,33);


(lib.Group2 = function() {
	this.initialize(img.Group2);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,68,19);


(lib.Group2copy2 = function() {
	this.initialize(img.Group2copy2);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,99,11);


(lib.Group4 = function() {
	this.initialize(img.Group4);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,208,27);


(lib.Layer3 = function() {
	this.initialize(img.Layer3);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,234,132);


(lib.Layer3_1 = function() {
	this.initialize();

	// Layer 2 (mask)
	var mask = new cjs.Shape();
	mask._off = true;
	mask.graphics.p("AIrKUIAAhgIysAAIAABgIoQAAIAA0nMAkjAAAIAAUng");
	mask.setTransform(117,66);

	// Layer 1
	this.instance = new lib.Layer3();

	this.instance.mask = mask;

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,234,132);


(lib.Symbol13 = function() {
	this.initialize();

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#000000").s().p("A3bTiMAAAgnDMAu2AAAMAAAAnDg");
	this.shape.setTransform(150,125);

	this.addChild(this.shape);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,300,250);


(lib.Symbol7 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_32 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).wait(32).call(this.frame_32).wait(1));

	// Layer 4 (mask)
	var mask = new cjs.Shape();
	mask._off = true;
	var mask_graphics_0 = new cjs.Graphics().p("Ag9AkQgCgEAAgMIAAgIIAGgGQACgFACgBIAJgFQADgFALgGIADgBIAAAAQALgQAJgCIAMgGQAKgEAKgBIAbAAIALAGQABABAAAGQAAAIgGAEIgWANIgTAKIgFAHIgCACQgFAFgHAGIgMAOQgMAKgMABQgSAAgFgLg");
	var mask_graphics_1 = new cjs.Graphics().p("AgQA4IgBgIQAAgMALgOQAIgMAPgEQAAgOAEgFIgGgHIgIAEIgRAMIgFAFIgCACQgEAFgKAHIgLANQgNALgMAAQgSAAgFgLQgCgEAAgMIAAgHIAHgFQABgFACgBIABgBQAGgMAOgNQAXgWAfgJIAoAAQAcAFALAGQADgCAGACQAMAEAAASQAAAMgCAGIgEAHQACADABAEQAAAIgKAMQgMAQgSAAIgIgBIgCAEQgTAXgWAAQgMAAgDgIg");
	var mask_graphics_2 = new cjs.Graphics().p("AgtBzIgBgIQABgIAGgJQAJgMAKAAIANACQAAgKATgLQAAgKAIgIQgKgHAAgMQgBgJgGgGIgBgBIgDAAQgMAAgDgGIgBgIQAAgLALgOQAIgNAPgEQAAgPAEgGIgGgGIgIADIgRAMIgFAHIgCACQgEAFgKAHIgLANQgNALgMAAQgSAAgFgLQgCgEAAgMIAAgHIAHgGQABgFACgCIABgBQAGgMAOgNQAXgWAfgIIAoAAQAcAFALAFQADgCAGACQAMAEAAASQAAAMgCAHIgEAGQACADABAGQAAAIgKAMQgJALgKADIAEAFIAGAHQACAFAFACQAHADACACQACADABAMQAAAMgCADIgBABIABAFIAAAOQAAALgFANQgIASgOAEQgDAEgFADQgLAKgTAEIgwAAIgGABQgLgBgDgGg");
	var mask_graphics_3 = new cjs.Graphics().p("AhrCaIAAkzIDEAAIAAArQADgBAEACQAMAEAAARQAAANgCAGIgEAHQACADABAGQAAAHgKAMIgGAIIAAAXIAEADQAHACACADQACADABALQAAAMgCAEIgBABIABAEIAAAPQAAAKgFAOQgFAIgEAFIAAA+g");
	var mask_graphics_4 = new cjs.Graphics().p("AidCaIAAkzIDEAAIAAArQAEgBADACIAFACQADgDAJAAIBIABQALgCAHAQQAFAMgBAJQAAAMgGAGIAAALQAAATgDAEQgCAGgIAFQABAEgBAEQAAAGgCAJQgEAIABANQAAAIgLAKQgMAMgQABIgfAAQgFgBgEgHQgBAKgFANQgEAIgFAFIAAA+g");
	var mask_graphics_5 = new cjs.Graphics().p("AjICaIAAkzIFtAAIAAAyIACgBQAFgDAJAAQAQAAADAUQACAKgCALIAAAXQgCAKgEAFIAAAFQAAAQgEAJQgFARgNAFQgBAKgGAMIAABsg");
	var mask_graphics_6 = new cjs.Graphics().p("AjxCaIAAkzIGwAAIAAAqIAhAAQANAEAEAJQABADAAAMQAAARgDAFIgFAGIABAMQAAAXgKAOIAAAMQAAAWgEAJQgGAMgNAGQgBAWgKAMIAABBg");
	var mask_graphics_7 = new cjs.Graphics().p("AkdCaIAAkzIGwAAIAAAqIAhAAQACgCAEABIBXAAQAPgFgCAnQAAASgCAGQgCAFgGAFQABAEgBAHQAAAUgMANIgFADQACAEAAAFQAAALgQAPIgFAGQgIAagIAJIAAAGIAAAQQgCAPgLABQgKgBgBgQIAAgPIgCggIgDgFIAAgJQgEgQAAgEIgBgDQgFgCgEgEQAAASgEAJQgGAMgNAGQgBAWgKAMIAABBg");
	var mask_graphics_8 = new cjs.Graphics().p("AltCaIAAkzIIoAAIAAApIAHAAQAOgFgCAnQAAASgBAGQgDAFgFAFIAAAFQAKAAACAQQABAIgBAJIgFArIA1AAQALADABAJQAAAKgNAIQgQAKgfABIgFABQABADABAEQAAALgPALIgEACIAAAsgAExggQgMAAAAgNIAAgCIAAgCQAAgEADgHIAGgSQAJgdAFgKQAEgHAPAAQALAAAGADQALAGABAMIACAlQAAANgCADQgDAKgQAIg");
	var mask_graphics_9 = new cjs.Graphics().p("AltCaIAAkzIIoAAIAAApIAHAAQAKgDACAQQAEgFAGgBIAVgDQAdgGALAVQAFALAAAQQAAATgJAMQgNAPghAHIgSAAIAAAHIgEAeIAEgCIAYAAIASgLIALgGIACgFIACgEQgDgFAAgEQAAgJAHgCQAJgEAGgOQADgKAFAAIADgIIAGgSQAJgdAFgKQAEgHAPAAQALAAAGADQALAGABAMIACAlQAAANgCADQgCAFgDADQAHAEgCAKIgBAWQgCAMgFAHQAAAVgDAXIAAAYQgBANgNAIIhoAAIgbgHIgEAAQgFAGgIAHIgEACIAAAsg");
	var mask_graphics_10 = new cjs.Graphics().p("AnZCaIAAkzIMmAAIAAEzgAGMgMQgDgDAAgJQAAgFAHgWIAGgVQgEgNAAgGQAAgKANgIQAPgLAaAAQALAAAFAIQACAFAAAEIgCAGQgEADgBAFIAAAFQAAAEgCADIgDANQgCAKgCACIgNAmQgCAKgQAAQgYAAgHgIg");
	var mask_graphics_11 = new cjs.Graphics().p("AnZCaIAAkzIMmAAIAAEzgAGRB0IgDgNQAAgIgGgFQgHgFAAgFQAAgIALgVIADgGIgDgLIgDgbIAAgQIgZABQgMAAgFgGQgBgCAAgFQAAgGACgCIADgBIgFgLIABgJIAAgKQADgGAOAAIAQACQABAAAHgJQAHgJAEAAIgBgJQAAgKANgIQAPgLAaAAQALAAAFAIQACAFAAAEIgCAGQgEADgBAFIAAAFQAAAEgCADIgDANQgCAKgCACIgNAmIABAEQABAKgBAKIAAAaIgDALIAAACIAAAeIAAAbQgDAVgSAAQgQAAgFgLg");
	var mask_graphics_12 = new cjs.Graphics().p("AoJCaIAAkzIMmAAIAAEzgAFiB0IgDgNQAAgIgGgFQgHgFAAgFQAAgGAGgNIgLgEIgSgIQgQgEgGgFQgEgEAAgIQAAgGAFgOQAHgLAFgEIADgDIgFgEQgBgCgBgFQABgGACgCIADgBIgFgLIAAgJIABgKQADgGAOAAIAQACQABAAAHgJQAHgJAEAAIgBgJQAAgKAMgIQAQgLAaAAQAJAAAFAGQACgBADABIASgEIA3AAQAOAFAEAJQACAGAAAKQgTA0gbABQgDADgFAAIgFgCIgFAAQgCAFgCACQgHADgGgBQgIgBgMADIgRAGIgEABIgBACIABAEQABAKgBAKIAAAaIgDALIAAACIAAAeIAAAbQgDAVgSAAQgQAAgFgLgAHWhdIADgCIgFAAIACACg");
	var mask_graphics_13 = new cjs.Graphics().p("ApGCaIAAkzIPSAAIAAAjIAsAAQAOAFAEAJQACAGAAAKIgBAEIACAAIgBgLIABgKQACgHAJgBIBfAAQAPAGAAAMIgCAKIADAHQAAAFgEAEQgEAEgFAAIgEgBIgEgCIgNAAQAAAEgFAFQgFAFgGAAQgFAAgFgFIhBAAIgIABIgIASQgPAdAAAEQAAAJgOAEQgGABgCAGIgFAKIgEAHIAAB8g");
	var mask_graphics_14 = new cjs.Graphics().p("ApGCaIAAkzIQeAAIAAAoIBfAAQAPAGAAAMIgCAKIADAHQAAAFgEAEQgEAEgFAAIgEgBIgEgCIgNAAQAAAEgFAFQgFAFgGAAIgFgBQgRAugNAOQAEAKABAHIAXAAQAdAJAKAIQAHAIAAANQAAARgMAGQgFAEgvAPIgkAAIAAA0g");
	var mask_graphics_15 = new cjs.Graphics().p("ApuCaIAAkzIR7AAIAAAoIACAAQAPAGAAAMIgCAKIADAHQAAAEgCADQAMAHADAYQADASgDASQAAAggDAOQgFAcgRACQgCAFgEADIAABKgAI1AOIgCgMQABgOACgNQACgLADgHIAAgLQAAgHADgRQADgTAEgCQAegRAKASQACAFAAAUQAAARgCAKIgDAEIAAACQAAAUgDAQQgIAdgUAAQgQAAgGgLg");
	var mask_graphics_16 = new cjs.Graphics().p("AqmCaIAAkzITAAAIAAAVQAcgHAOAAQAJAAAHAFIAcAAQAkAMALAKQAJAIAAATQgBAQgBAEQgFALgUAFIgbAAQgagIgJgUIgBgCQgFADgIADIgLADQAAAOgCAKIgCAEIAAACIAAASIABAIIAAAVQAAAZgEALQgFAPgQAGIAABag");
	var mask_graphics_17 = new cjs.Graphics().p("AsHCaIAAkzIVXAAIAABRQABAGAAAJIgBApIAAAgIABAFQADALAAAIQAAAKgCAEIgCAGIAABegALNglQgGgDgIAAQgHAAgDgFQgCgEAAgEQAAgGAKgUIAMgXIAOgYQAHgRAGABIAPAGIAEgCQADgCADAAQALAAAAANQAAAKgHAmQgJAfgGAGQgGAHgOAAQgLAAgGgCg");
	var mask_graphics_18 = new cjs.Graphics().p("AsHCaIAAkzIVXAAIAABRQABAGAAAJIgBApIAAAgIABAFQADALAAAIQAAAKgCAEIgCAGIAABegAKTBAQgCgHAAgWQAAgYAFgLQADgKAJgCQAAgKAEgMQAFgNAIgEIAAgCQAAgGAKgUIAMgXIAOgYQAHgRAGABIAPAGIAEgCQADgCADAAQALAAAAANQAAAKgHAmQgJAdgFAHQgGAmgJAOIAAAEQAAAQgJAYQgNAkgXAAQgdAAgHgZgAJmhCQgPgPAAgLQAAgKADgDQAHgHAZADQADgFAOgCQAMgEAJAAQATAAAAARQAAAOgFAQQgJAbgRAAQgcAAgSgUg");
	var mask_graphics_19 = new cjs.Graphics().p("As4CaIAAkzIXjAAIAAARQAEgHAEABIAPAGIAEgCQADgCADAAQAKAAABAKQAjgGAMAKQAFAEAAAKQAAAGgDAHIADAGIAiAAQAJACACAGIABAKQAAAOgCAHQgGASgUAAQgrAAgNgNQgGgFAAgQIgCAAIgBAAIgDAHQgIASgPAEQgHAkgIAOIAAAEQAAAOgIAXIAABog");
	var mask_graphics_20 = new cjs.Graphics().p("AtkCaIAAkzIXkAAIAAARQAEgHAEABIAOAGIAEgCQADgCADAAQALAAABAKQAigGAMAKQAFAEAAAKQAAAGgCAHIACAGIAjAAQAJACACAGQAAgNADgHQAGgNAUgDIAAAAIAhAAQASAMAEAFQAEAFAAALQAAAKgFAKQgHAOgNACIgsAAQgOAAgDgMIgBgFQgBAJgCAGQgDAKgHADQgEANgNAbIgFAVIgGANQAAAPgGAQQgLAegbAAQgRAAgHgQQgEgJAAgJQAAgOAMgZQAHgOAHgCIABgIIgCgLIgBgNIABgRIABgHQgEgGAAgNIgBAAIgBAAIgDAHQgIASgQAEQgGAkgJAOIAAAEQAAAOgHAXIAABog");
	var mask_graphics_21 = new cjs.Graphics().p("AuACaIAAkzIajAAIAAAgIAKAAQASAMAFAFQADAFABALQgBAKgEAKIgEAHQAAARgJAdIgEARIAAAFQAAAQgPAgIAAABIAABigANSg1IAAgPQAAgVAEgLQAGgTASgFQAOgBACALQABAHgCAMIACAQIACAPQAAAIgEAHQgHAKgNAAQgSAAgFgOg");
	var mask_graphics_22 = new cjs.Graphics().p("AusCaIAAkzIajAAIAAAgIAKAAQASAMAFAFQADAEABAGIAHADIAFgEIACgFQAGgTASgFQANgBACAKIAMgGQACgOAnAEQAoAGgBAbQABAHgCAEQgEAHgLACIgmAIQgJADgQgBQgGABgDgCIAAADQAIACAAAKIgFALQgFAJAAAaQAAAYgPAKIAAABQAAAJgCAEIgFAVQgFAYgHAFQgDACgMAAQgaAAgHgQIAAgDQgDADgEAAIgDgBIAABPg");
	var mask_graphics_23 = new cjs.Graphics().p("AuzCaIAAkzIcSAAIAAAXQAKgDAWACQAoAGAAAbQAAAHgBAEQgEAHgLACIgmAIQgHACgLAAIAAAOIBGAAQALADADAEQABACAAAFQAAAag6AWIgSAHQANAKACAGQAIAeAAALQAAAMgCACQgDAGgQADIgLAAIAAA7g");
	var mask_graphics_24 = new cjs.Graphics().p("Av8CaIAAkzIeTAAIAAA4QABgDABgHQAGgFAeAAIAfAAQAaASAEADQADAFAAALQAAAOgFAEQgFAEgNAEIAAAHQAAAWgEAMQgJAYgdAAQgeAAgGgUIAAgnIgBgCIAADHg");
	var mask_graphics_25 = new cjs.Graphics().p("AwPCaIAAkzIfPAAIAAApIAJAAQAaASAEADQADAFAAALQAAAOgFAEQgFAEgNAEIAAAHQAAARgCALIASAEIATAHQAJADAGAEQACADAEAJIADAUIACAVQAAASgWAOQgTALgLAAIgDAAIABADQAAAGgCACQgEAGgNAAIgCAIQgBADgEABIAAAZg");
	var mask_graphics_26 = new cjs.Graphics().p("AwtCaIAAkzIfPAAIAAApIAJAAQAaASAEADQACADABAHIABAAIgBgGQABgOAEgFQAIgKAbgHIAbAAQARAHAFAMQADAGgBAPQABANgIAQQAFACAEAGQADACAAAHIAAATQAAANADAXQAAAUgKAPIgFARQgFAOgNAAQgQABgNgTQgFAMgQALQgTALgLAAIgDAAIABADQAAAGgCACQgEAGgNAAIgCAIQgBADgEABIAAAZg");
	var mask_graphics_27 = new cjs.Graphics().p("AxxCiIAAk0MAg2AAAIAAAfIAGAAQARAGAFANQADAFgBAPQAAAIgBAHIADgBIAAgDIAAgNQACgGALgMIAAAAIgBgGQAAgNASgWQARgVANgDIAtAAIAdARQAHAGABAKQgBAJgEACQgDAEgRAIIgMAKIgEADIgBAGQAAAXgCAKQgEAYgPADIAAADQAAAPgBAEQgHAOgCAMIgJAhQgFAMgOgBQgBAQgIAQQgKAUgTALIgdAAIgGgCIAAAjgAPcgvIADABIgBgBIgBgBIgBABg");
	var mask_graphics_28 = new cjs.Graphics().p("AyJCiIAAk0MAh8AAAQANgNAJgCIAuAAIAdARQAHAGAAAKIgBAFIAUAAQAcAFAAAVQAAAEgOAjIgEATIgFAIIgBAEQAAAZgEAKIgDAGIAAADQAAAPgRAbQgWAjgeAAQgaABgHgUQgCgEAAgHIgHACIAABgg");
	var mask_graphics_29 = new cjs.Graphics().p("Ay2CiIAAk0MAh8AAAQAMgNAKgCIAuAAIAcARQAIAGAAAKIgBAFIATAAQAVAEAGALIAOgFQAHgEAVgBQAZAAAEAIIAEAaIABAUIAAAJQAQANAAARQAAAJgJANIgOASQgHAJgNAKQgQAMgPgBIgcAAQgRgBgHgJQgEALgIANQgHAMgIAHIAABUg");
	var mask_graphics_30 = new cjs.Graphics().p("AzuCiIAAk0MAh8AAAQAMgNAKgCIAtAAIAaAPIBtAAIAAAcQAPABADAHIAEAaIAAAUIAAAJQARANAAARQAAAJgJANIgMAPIAEAAQAJAAAGACIAAgBQAAgNAXACQgDgCgFAAQgGgBgBgBIgDgFIAAgEQAAgEAFgEQAHgDAAgHQAAgNANgDIACgBQgLAAAAgMQAAgLAMgBIADAAIADgGQAGgLAAgWQABgYABgEQAFgQAUALQAVgEAFAZQADANgBAWQAKABADADQACADAAAEQAAAFgLALIABAJQAAAHgHAPIgJAYIgDAJQgEAIgGADQgGAEgOACIgSAEIgbAAIAAAJQAAAPgLANQgMANgZAIIAABOg");
	var mask_graphics_31 = new cjs.Graphics().p("A0iCiIAAk0MAh9AAAQAMgNAKgCIAtAAIAZAPIBtAAIAAAcQAPABADAHIAFAaIAAAUIAAAJQARANAAARQAAAJgJANIgNAPIAFAAQAJAAAFACIAAgBQAAgNAXACQgCgCgGAAQgFgBgBgBIgEgFIAAgEQAAgEAGgEQAGgDABgHQAAgNANgDIACgBQgLAAAAgMQAAgLAMgBIADAAIADgGQAGgLAAgWQAAgYACgEQAEgQAUALQAWgEAFAZQACANAAAWQAGABAEABQAFgGAJgIQAZgXANADIAaAAQAVAMAFAUQADAJAAAcIAAAWQgDAOgMAEIgCAIIgOAVQgEAMgFAIQgJARgPAAIgDAAIAAADQAAALgLAOQgQAUgbAAQgOAAgIgGIgKgEIgSgFIgbAAQgfAAgKgMIgQAGIAABOgASyg2IABgBIgBgDIAAAEg");
	var mask_graphics_32 = new cjs.Graphics().p("A0/CiIAAk0MAh8AAAQAMgNAKgCIAtAAIAaAPIDIAAQAEgBAFABIDWAAIAAE0g");

	this.timeline.addTween(cjs.Tween.get(mask).to({graphics:mask_graphics_0,x:6.2,y:11.9}).wait(1).to({graphics:mask_graphics_1,x:9.1,y:12.6}).wait(1).to({graphics:mask_graphics_2,x:9.1,y:18.4}).wait(1).to({graphics:mask_graphics_3,x:7.6,y:18.4}).wait(1).to({graphics:mask_graphics_4,x:12.5,y:18.4}).wait(1).to({graphics:mask_graphics_5,x:16.8,y:18.4}).wait(1).to({graphics:mask_graphics_6,x:21,y:18.4}).wait(1).to({graphics:mask_graphics_7,x:25.4,y:18.4}).wait(1).to({graphics:mask_graphics_8,x:33.3,y:18.4}).wait(1).to({graphics:mask_graphics_9,x:33.3,y:18.4}).wait(1).to({graphics:mask_graphics_10,x:44.2,y:18.4}).wait(1).to({graphics:mask_graphics_11,x:44.2,y:18.4}).wait(1).to({graphics:mask_graphics_12,x:48.9,y:18.4}).wait(1).to({graphics:mask_graphics_13,x:55,y:18.4}).wait(1).to({graphics:mask_graphics_14,x:55,y:18.4}).wait(1).to({graphics:mask_graphics_15,x:59,y:18.4}).wait(1).to({graphics:mask_graphics_16,x:64.7,y:18.4}).wait(1).to({graphics:mask_graphics_17,x:74.4,y:18.4}).wait(1).to({graphics:mask_graphics_18,x:74.4,y:18.4}).wait(1).to({graphics:mask_graphics_19,x:79.3,y:18.4}).wait(1).to({graphics:mask_graphics_20,x:83.6,y:18.4}).wait(1).to({graphics:mask_graphics_21,x:86.6,y:18.4}).wait(1).to({graphics:mask_graphics_22,x:91,y:18.4}).wait(1).to({graphics:mask_graphics_23,x:91.6,y:18.4}).wait(1).to({graphics:mask_graphics_24,x:99,y:18.4}).wait(1).to({graphics:mask_graphics_25,x:100.9,y:18.4}).wait(1).to({graphics:mask_graphics_26,x:103.9,y:18.4}).wait(1).to({graphics:mask_graphics_27,x:110.7,y:17.6}).wait(1).to({graphics:mask_graphics_28,x:113,y:17.6}).wait(1).to({graphics:mask_graphics_29,x:117.5,y:17.6}).wait(1).to({graphics:mask_graphics_30,x:123.2,y:17.6}).wait(1).to({graphics:mask_graphics_31,x:128.3,y:17.6}).wait(1).to({graphics:mask_graphics_32,x:131.3,y:17.6}).wait(1));

	// Layer 1
	this.instance = new lib.bult();

	this.instance.mask = mask;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(33));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,7.2,12.7,9.4);


(lib.loñmnlonoi = function() {
	this.initialize();

	// Layer 5
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("ADjA+QgQgKgKgQQgIgQgBgUIAAAAQABgNAFgOQAFgNAKgKQAKgJANgGQAOgGAQAAQAVAAARAKQAQAJAJARQAJAQAAATIAAAAQABAOgGANQgFANgKALQgKAKgNAFQgNAGgRAAQgVgBgRgJgADxgqQgLAHgGALQgGAMAAAMIAAAAQAAANAHAMQAFALALAHQALAGAOABQANgBALgGQALgHAGgLQAGgLAAgOIAAAAQAAgNgGgLQgHgLgKgHQgLgGgOAAQgOAAgKAGgAIBBGIAAiLIBoAAIAAAXIhPAAIAAAkIBGAAIAAAUIhGAAIAAAlIBQAAIAAAXgAHLBGIgjgyIgfAAIAAAyIgYAAIAAiLIA+AAQANAAAKAEQALAEAGAHQAGAFADAIQADAIAAAIIAAABQAAALgFAJQgDAHgIAFQgHAGgKACIAmA2gAGJAAIAkAAQAOAAAHgGQAIgFAAgMIAAAAQAAgMgIgFQgHgGgOAAIgkAAgACKBGIAAhjIgsBAIgBAAIgsg/IAABiIgYAAIAAiLIAaAAIArBDIAqhDIAbAAIAACLgAhZBGIhOhiIAABiIgYAAIAAiLIAXAAIBLBfIAAhfIAYAAIAACLgAj2BGIgigyIgfAAIAAAyIgZAAIAAiLIA/AAQANAAALAEQAKAEAHAHQAFAFADAIQADAIAAAIIAAABQAAALgEAJQgFAHgHAFQgIAGgKACIAnA2gAk3AAIAkAAQANAAAIgGQAIgFAAgMIAAAAQAAgMgIgFQgIgGgNAAIgkAAgAmBBGIgPgjIhCAAIgPAjIgZAAIA+iMIAXAAIA+CMgAnJAOIAwAAIgYg2gApzBGIAAiLIBnAAIAAAXIhPAAIAAAkIBGAAIAAAUIhGAAIAAAlIBRAAIAAAXgArwBGIAAiLIAZAAIAAB0IBJAAIAAAXgAKRAfIBIgfIhIgeIAAgYIBfAtIAAATIhfAsg");
	this.shape.setTransform(69.1,25.8);

	this.addChild(this.shape);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(-10,11.6,157,28);


(lib.kkkSymbol2jojojoj = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#000000").s().p("A3bTiMAAAgnDMAu2AAAMAAAAnDg");
	this.shape.setTransform(150,125);
	this.shape._off = true;

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(3).to({_off:false},0).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = null;


(lib.jijx_menuButtoncopy2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FFFFFF").s().p("AsVCVIAAkqIYrAAIAAEqg");
	this.shape.setTransform(79,15);
	this.shape._off = true;

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(3).to({_off:false},0).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = null;


(lib.hhSymbol3 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_14 = function() {
		/* stop();*/
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).wait(14).call(this.frame_14).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = null;


(lib.Group2copy = function() {
	this.initialize();

	// Layer 1
	this.instance = new lib.Group2copy2();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,99,11);


(lib.lllGroup4 = function() {
	this.initialize();

	// Layer 1
	this.instance = new lib.Group4();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,208,27);


(lib.lllGroup2 = function() {
	this.initialize();

	// Layer 1
	this.instance = new lib.Group2();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,68,19);


(lib.kkkSymbol10 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{over:1,out:5});

	// timeline functions:
	this.frame_0 = function() {
		this.stop();
	}
	this.frame_4 = function() {
		this.stop();
	}
	this.frame_8 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(4).call(this.frame_4).wait(4).call(this.frame_8).wait(1));

	// LEARN MORE ›
	this.instance = new lib.loñmnlonoi();
	this.instance.setTransform(47.1,6.6,0.658,0.658,0,0,0,73,25.6);
	this.instance.filters = [new cjs.ColorFilter(0, 0, 0, 1, 255, 255, 255, 0)];
	this.instance.cache(-12,10,161,32);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({scaleX:0.7,scaleY:0.7},4).to({scaleX:0.66,scaleY:0.66},4).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-5.1,2,99.1,9.5);


(lib.comdutchmonacobanneritemsReplay = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.instance = new lib.hhSymbol3();
	this.instance.setTransform(8.5,8.5,1,1,0,0,0,8.5,8.5);

	this.shape = new cjs.Shape();
	this.shape.graphics.f("#FF0000").s().p("AiGCGIAAkMIEMAAIAAEMg");
	this.shape.setTransform(8.5,8.5);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[]}).to({state:[{t:this.instance}]},1).to({state:[{t:this.shape}]},2).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = null;


(lib.kkkSymbol11 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		var btn = this.btn;
		var mc_anim = this.mc_anim;
		
		btn.on('mouseover', function(){mc_anim.gotoAndPlay('over');});
		btn.on('mouseout', function(){mc_anim.gotoAndPlay('out');});
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(1));

	// btn
	this.btn = new lib.jijx_menuButtoncopy2();
	this.btn.setTransform(-12.4,-9,0.703,1.034,0,0,0,-0.6,0);
	new cjs.ButtonHelper(this.btn, 0, 1, 2, false, new lib.jijx_menuButtoncopy2(), 3);

	this.timeline.addTween(cjs.Tween.get(this.btn).wait(1));

	// mc_anim
	this.mc_anim = new lib.kkkSymbol10();

	this.timeline.addTween(cjs.Tween.get(this.mc_anim).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-12,-9,111,31);


// stage content:
(lib.YET15025_Tundra_Fish_0_300x250_html_0 = function(mode,startPosition,loop) {
if (loop == null) { loop = false; }	this.initialize(mode,startPosition,loop,{});

	// timeline functions:
	this.frame_0 = function() {
		this.on("click",function(evt){ 
		window.open(clickTag);
		});
	}
	this.frame_190 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).call(this.frame_0).wait(190).call(this.frame_190).wait(1));

	// Layer 3
	this.shape = new cjs.Shape();
	this.shape.graphics.f().s("#666666").ss(1,1,1).p("AXXzcMAAAAm5MgutAAAMAAAgm5g");
	this.shape.setTransform(150,125);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(191));

	// Layer 2
	this.instance = new lib.kkkSymbol11();
	this.instance.setTransform(378,234.5,0.986,0.986,0,0,0,65,15.5);
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(170).to({_off:false},0).to({x:217},16,cjs.Ease.get(1)).wait(5));

	// Layer 12
	this.btn = new lib.kkkSymbol2jojojoj();
	this.btn.setTransform(150,125,1,1,0,0,0,150,125);
	new cjs.ButtonHelper(this.btn, 0, 1, 2, false, new lib.kkkSymbol2jojojoj(), 3);

	this.timeline.addTween(cjs.Tween.get(this.btn).wait(191));

	// Layer 20
	this.instance_1 = new lib.Layer3_1();
	this.instance_1.setTransform(150,330,1,1,0,0,0,117,66);
	this.instance_1._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).wait(2).to({_off:false},0).to({y:140},23,cjs.Ease.get(1)).wait(166));

	// Layer 4
	this.instance_2 = new lib.lllGroup2();
	this.instance_2.setTransform(150,224.5,1,1,0,0,0,34,9.5);
	this.instance_2._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).wait(13).to({_off:false},0).wait(158).to({x:149.7},0).wait(1).to({x:148.7},0).wait(1).to({x:147},0).wait(1).to({x:144.3},0).wait(1).to({x:140.5},0).wait(1).to({x:135.4},0).wait(1).to({x:129},0).wait(1).to({x:121.5},0).wait(1).to({x:113.5},0).wait(1).to({x:105.9},0).wait(1).to({x:99.4},0).wait(1).to({x:94.5},0).wait(1).to({x:90.9},0).wait(1).to({x:88.6},0).wait(1).to({x:87.4},0).wait(1).to({x:87},0).wait(5));

	// bult.png
	this.instance_3 = new lib.Symbol7();
	this.instance_3.setTransform(150.5,41.5,1,1,0,0,0,131.5,16.5);
	this.instance_3._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_3).wait(133).to({_off:false},0).wait(58));

	// Layer 23
	this.instance_4 = new lib.Symbol13();
	this.instance_4.setTransform(150,62.3,1,0.498,0,0,0,150,125);
	this.instance_4.alpha = 0;
	this.instance_4._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_4).wait(119).to({_off:false},0).to({alpha:1},13).wait(59));

	// Layer 9 (mask)
	var mask = new cjs.Shape();
	mask._off = true;
	var mask_graphics_48 = new cjs.Graphics().p("ApZD7IAAn1ISzAAIAAH1g");
	var mask_graphics_56 = new cjs.Graphics().p("AurD7IAAn1IdWAAIAAH1g");
	var mask_graphics_63 = new cjs.Graphics().p("AyvD7IAAn1MAlfAAAIAAHwIoJAAIAAAFg");

	this.timeline.addTween(cjs.Tween.get(mask).to({graphics:null,x:0,y:0}).wait(48).to({graphics:mask_graphics_48,x:88,y:51.6}).wait(8).to({graphics:mask_graphics_56,x:121.8,y:51.6}).wait(7).to({graphics:mask_graphics_63,x:147.8,y:51.6}).wait(96).to({graphics:null,x:0,y:0}).wait(32));

	// Layer 1
	this.instance_5 = new lib.lllGroup4();
	this.instance_5.setTransform(149,50.5,1,1,0,0,0,104,13.5);
	this.instance_5._off = true;

	this.instance_5.mask = mask;

	this.timeline.addTween(cjs.Tween.get(this.instance_5).wait(48).to({_off:false},0).to({_off:true},111).wait(32));

	// Layer 5 (mask)
	var mask_1 = new cjs.Shape();
	mask_1._off = true;
	var mask_1_graphics_63 = new cjs.Graphics().p("AhwD5IAAnxIRyAAIAAHxg");
	var mask_1_graphics_64 = new cjs.Graphics().p("AiyD5IAAnxITKAAIAAHxg");
	var mask_1_graphics_65 = new cjs.Graphics().p("AjwD5IAAnxIUeAAIAAHxg");
	var mask_1_graphics_66 = new cjs.Graphics().p("AksD5IAAnxIVuAAIAAHxg");
	var mask_1_graphics_67 = new cjs.Graphics().p("AlkD5IAAnxIW6AAIAAHxg");
	var mask_1_graphics_68 = new cjs.Graphics().p("AmYD5IAAnxIYAAAIAAHxg");
	var mask_1_graphics_69 = new cjs.Graphics().p("AnKD5IAAnxIZDAAIAAHxg");
	var mask_1_graphics_70 = new cjs.Graphics().p("An5D5IAAnxIaCAAIAAHxg");
	var mask_1_graphics_71 = new cjs.Graphics().p("AokD5IAAnxIa8AAIAAHxg");
	var mask_1_graphics_72 = new cjs.Graphics().p("ApMD5IAAnxIbyAAIAAHxg");
	var mask_1_graphics_73 = new cjs.Graphics().p("ApxD5IAAnxIckAAIAAHxg");
	var mask_1_graphics_74 = new cjs.Graphics().p("AqSD5IAAnxIdQAAIAAHxg");
	var mask_1_graphics_75 = new cjs.Graphics().p("AqxD5IAAnxId6AAIAAHxg");
	var mask_1_graphics_76 = new cjs.Graphics().p("ArMD5IAAnxIeeAAIAAHxg");
	var mask_1_graphics_77 = new cjs.Graphics().p("ArkD5IAAnxIe+AAIAAHxg");
	var mask_1_graphics_78 = new cjs.Graphics().p("Ar5D5IAAnxIfbAAIAAHxg");
	var mask_1_graphics_79 = new cjs.Graphics().p("AsKD5IAAnxIfyAAIAAHxg");
	var mask_1_graphics_80 = new cjs.Graphics().p("AsZD5IAAnxMAgGAAAIAAHxg");
	var mask_1_graphics_81 = new cjs.Graphics().p("AskD5IAAnxMAgUAAAIAAHxg");
	var mask_1_graphics_82 = new cjs.Graphics().p("AssD5IAAnxMAgfAAAIAAHxg");
	var mask_1_graphics_83 = new cjs.Graphics().p("AsxD5IAAnxMAgmAAAIAAHxg");
	var mask_1_graphics_84 = new cjs.Graphics().p("AsyD5IAAnxMAgnAAAIAAHxg");

	this.timeline.addTween(cjs.Tween.get(mask_1).to({graphics:null,x:0,y:0}).wait(63).to({graphics:mask_1_graphics_63,x:102.6,y:7.5}).wait(1).to({graphics:mask_1_graphics_64,x:104.9,y:7.5}).wait(1).to({graphics:mask_1_graphics_65,x:107,y:7.5}).wait(1).to({graphics:mask_1_graphics_66,x:109.1,y:7.5}).wait(1).to({graphics:mask_1_graphics_67,x:111,y:7.5}).wait(1).to({graphics:mask_1_graphics_68,x:112.8,y:7.5}).wait(1).to({graphics:mask_1_graphics_69,x:114.6,y:7.5}).wait(1).to({graphics:mask_1_graphics_70,x:116.2,y:7.5}).wait(1).to({graphics:mask_1_graphics_71,x:117.7,y:7.5}).wait(1).to({graphics:mask_1_graphics_72,x:119,y:7.5}).wait(1).to({graphics:mask_1_graphics_73,x:120.3,y:7.5}).wait(1).to({graphics:mask_1_graphics_74,x:121.5,y:7.5}).wait(1).to({graphics:mask_1_graphics_75,x:122.5,y:7.5}).wait(1).to({graphics:mask_1_graphics_76,x:123.5,y:7.5}).wait(1).to({graphics:mask_1_graphics_77,x:124.3,y:7.5}).wait(1).to({graphics:mask_1_graphics_78,x:125,y:7.5}).wait(1).to({graphics:mask_1_graphics_79,x:125.6,y:7.5}).wait(1).to({graphics:mask_1_graphics_80,x:126.1,y:7.5}).wait(1).to({graphics:mask_1_graphics_81,x:126.5,y:7.5}).wait(1).to({graphics:mask_1_graphics_82,x:126.8,y:7.5}).wait(1).to({graphics:mask_1_graphics_83,x:126.9,y:7.5}).wait(1).to({graphics:mask_1_graphics_84,x:127,y:7.5}).wait(75).to({graphics:null,x:0,y:0}).wait(32));

	// Symbol 9
	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFFFF").s().p("AJwAPIAAgdIGbAAIAAAdgAwKAPIAAgdIGbAAIAAAdg");
	this.shape_1.setTransform(149.5,22.5);
	this.shape_1._off = true;

	this.shape_1.mask = mask_1;

	this.timeline.addTween(cjs.Tween.get(this.shape_1).wait(63).to({_off:false},0).to({_off:true},96).wait(32));

	// Layer 6
	this.instance_6 = new lib.Group2copy();
	this.instance_6.setTransform(149.5,22.5,1,1,0,0,0,49.5,5.5);
	this.instance_6.alpha = 0;
	this.instance_6._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance_6).wait(26).to({_off:false},0).to({alpha:1},18).wait(147));

	// Layer 13
	this.instance_7 = new lib.Symbol13();
	this.instance_7.setTransform(150,125,1,1,0,0,0,150,125);

	this.timeline.addTween(cjs.Tween.get(this.instance_7).wait(191));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(149.5,124.5,301,251);

})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{});
var lib, images, createjs, ss;