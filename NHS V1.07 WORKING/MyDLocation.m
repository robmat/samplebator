

#import "MyDLocation.h"
#import "DLocationManager.h"

@implementation MyDLocation

@synthesize delegate, locationManager, currentLocation;

- (id) init {
	self = [super init];
	if (self != nil) {
		self.locationManager = [[[DLocationManager alloc] init] autorelease];
		self.locationManager.delegate = self; // Tells the location manager to send updates to this object
		[self.locationManager startUpdatingLocation];
	}
	return self;
}

- (void)dealloc
{
	[currentLocation release];
	
	[super dealloc];
}

#pragma mark Delegate Methods

- (void)locationManager:(DLocationManager *)manager
	didUpdateToLocation:(CLLocation *)newLocation
		   fromLocation:(CLLocation *)oldLocation
{
	if(currentLocation != newLocation)
	{
		[currentLocation release];
		currentLocation = nil;
		
		currentLocation = newLocation;
		
		[currentLocation retain];
	}
}

@end
