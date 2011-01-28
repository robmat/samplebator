

#import <CoreLocation/CoreLocation.h>

#define LOCATIONS_PATH @"/absolute/path/to/location/text/file.txt"

@protocol DLocationManagerDelegate;

@interface DLocationManager : CLLocationManager {

#ifdef TARGET_IPHONE_SIMULATOR
	//NSArray *locations;
	//NSString *fake_location;
	CLLocation *spoofed_location;
#endif
	
}

@property(assign, nonatomic) id<DLocationManagerDelegate> delegate;

@end
