//
//  POI.m
//  BigApp1
//
//  Created by Andrew  Farmer on 01/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//



#import "POI.h"
@implementation POI

@synthesize coordinate ;
@synthesize subtitle ;
@synthesize title ;

- (id) initWithCoords:(CLLocationCoordinate2D) coords{
	self = [super init] ;
	if (self != nil ){
		coordinate = coords ;
	}
	return self ;
}

- (void) dealloc{
	[title release] ;
	[subtitle release] ;
	[super dealloc];

}

@end
