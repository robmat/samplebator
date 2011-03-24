
#import "MyAnnotation.h"


@implementation MyAnnotation

@synthesize coordinate, title, subtitle, tempo;

- (id) initWithDictionary:(NSDictionary *) dict
{

	self = [super init];
	if (self != nil) {
		coordinate.latitude = [[dict objectForKey:@"latitude"] doubleValue];
		coordinate.longitude = [[dict objectForKey:@"longtitude"] doubleValue];

		self.title = [dict objectForKey:@"name"];
		self.subtitle = [dict objectForKey:@"postcode"];

		//self.subtitle.text = @"00117 54548 4545";
		
		self.tempo = @"TextoTemporal" ;
		
		
	}
	return self;
}


@end
