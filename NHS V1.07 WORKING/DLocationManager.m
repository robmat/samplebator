

#import "DLocationManager.h"
//#import <CoreLocation/CLLocationManager.h>

@implementation DLocationManager

@dynamic delegate;

#ifdef TARGET_IPHONE_SIMULATOR

- (id)init
{
	self = [super init];
	
	//Create array of locations
	//locations = [[NSArray alloc] initWithContentsOfFile:LOCATIONS_PATH];
	//[locations retain]
	

	
	return self;
}

- (void)dealloc
{
	//[locations release];
	[spoofed_location release];
	[super dealloc];
}

- (void)startUpdatingLocation
{
	//do not call super
	[super startUpdatingLocation];
	//NSString *fake_location = [[NSString alloc] initWithContentsOfFile:LOCATIONS_PATH]; 
	//NSArray *latLong = [fake_location componentsSeparatedByString:@","];
 	double lon = -1.386233;
	double lat = 54.091594;
	
	spoofed_location = [[CLLocation alloc] initWithLatitude:lat longitude:lon];
	
	[self.delegate locationManager:self didUpdateToLocation:spoofed_location fromLocation:nil];
}

#endif

@end
