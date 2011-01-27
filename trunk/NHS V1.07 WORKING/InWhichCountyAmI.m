//
//  InWhichCountyAmI.m
//  Polygon
//
//  Created by User on 12/9/10.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import "InWhichCountyAmI.h"
#import "PointInPolygonCheckAlgorithm.h"

@implementation InWhichCountyAmI
@synthesize nameDict;
- (InWhichCountyAmI*) init {
	NSArray* countiesNames = [NSArray arrayWithObjects:	@"yorkshire_coord",
														@"wakefield_coord",
														@"sheffield_coord",
														@"north_lincolnshire_coord",
														@"kirklees_coord",
														@"hull_coord",
														@"east_riding_coord",
														@"doncaster_coord",
														@"calderdale_coord",
														@"bradford_coord",
														@"barnsley_coord",
														@"leeds_coord",
														nil];
	NSArray* countiesRealNames = [NSArray arrayWithObjects:	@"NHS Yorkshire and the Humber",
															@"NHS Wakefield",
															@"NHS Sheffield",
															@"NHS North Lincolnshire",
															@"NHS Kirklees",
															@"NHS Hull",
															@"NHS East Riding",
															@"NHS Doncaster",
															@"NHS Calderdale",
															@"NHS Bradford",
															@"NHS Barnsley",
															@"NHS Leeds",
															nil];
	
	nameDict = [NSDictionary dictionaryWithObjects:countiesRealNames forKeys:countiesNames];
				
	countyDict = [[NSMutableDictionary alloc] init];
	for (NSString* countyName in countiesNames) {
		PointInPolygonCheckAlgorithm* alg = [[PointInPolygonCheckAlgorithm alloc] init];
		[alg setUp:countyName];
		[countyDict setObject:alg forKey:countyName];
		[alg release];
	}
	return self;
}
- (CLLocation*) giveCountyCenterWithCountyId: (NSString*) countyId {
	PointInPolygonCheckAlgorithm* alg = [countyDict objectForKey:countyId];
	return (CLLocation*) [alg givePolygonCenter];
}
- (NSString*) giveCountyWithLongtitude: (double) lon latitude: (double) lat {
	for (NSString* countyName in [countyDict keyEnumerator]) {
		PointInPolygonCheckAlgorithm* alg = [countyDict objectForKey:countyName];
		[alg setPx:lon + 180];
		[alg setPy:lat + 180];
		if ([alg isInside]) {
			return countyName;
		}	
	}
	return nil;
}
- (void) dealloc {
	[super dealloc];
	[countyDict release];
}	
@end
