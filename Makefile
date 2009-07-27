install:
	rm -rf build
	mkdir build
	cp -ruv site build/site
	cp -ruv media build/media
	cp -ruv admin build/admin
	cp -uv install.xml build/install.xml
	cd ./build ;\
	7z a -mx9 -tzip com_beursplein *
