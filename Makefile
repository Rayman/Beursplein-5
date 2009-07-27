
install:
	mkdir -pv build
	cp -ruv site build/site
	cp -ruv media build/media
	cp -ruv admin build/admin
	cp -uv install.xml build/install.xml
	cd build;\
	7z u -mx9 -tzip com_beursplein *
clean:
	rm -rf build
