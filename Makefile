install:
	mkdir -pv build
	cp -ruv site build
	cp -ruv media build
	cp -ruv admin build

	cp -uv install.xml build/install.xml
	cd build;\
	7z u -mx9 -tzip com_beursplein *
clean:
	rm -rf build
