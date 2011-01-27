//
//  PointInPolygonCheckAlgorithm.m
//  Polygon
//
//  Created by User on 12/6/10.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import "PointInPolygonCheckAlgorithm.h"
#import <math.h>
#import <CoreLocation/CoreLocation.h>

@implementation PointInPolygonCheckAlgorithm
@synthesize px, py;
- (void) setUp: (NSString*) path {
	NSString* file = [[NSBundle mainBundle] pathForResource:path ofType:@"plist"];
	NSMutableDictionary* dict = [[NSMutableDictionary alloc] initWithContentsOfFile:file];
	NSArray* lonArr = [dict objectForKey:@"longtitude"];
	NSArray* latArr = [dict objectForKey:@"latitude"];
	polygonx = [[NSMutableArray alloc] init];
	polygony = [[NSMutableArray alloc] init];
	for (NSString* lon in lonArr) {
		NSNumber* number = [NSNumber numberWithFloat:[lon floatValue]];
		float numFloat = [number floatValue];
		numFloat = numFloat + 180;
		[polygonx addObject:[NSNumber numberWithFloat:numFloat]];
	}
	for (NSString* lat in latArr) {
		NSNumber* number = [NSNumber numberWithFloat:[lat floatValue]];
		float numFloat = [number floatValue];
		numFloat = numFloat + 180;
		[polygony addObject:[NSNumber numberWithFloat:numFloat]];	
	}	
	n = [polygonx count];
	[dict release];
}
- (double) ownerityXX: (double)xx XY:(double)xy YX:(double)yx YY:(double)yy ZX:(double)zx ZY:(double)zy {
	double	det = xx*yy + yx*zy + zx*xy - zx*yy - xx*zy - yx*xy;
	if (det!=0) 
		return 0; 
	else {
		if ((fmin(xx, yx) <= zx) && (zx <= fmax(xx, yx)) &&
			(fmin(xy, yy) <= zy) && (zy <= fmax(xy, yy)))
			return 1;
		else
			return 0;
	}}	
- (double) detXX: (double)xx XY:(double)xy YX:(double)yx YY:(double)yy ZX:(double)zx ZY:(double)zy {
	return (xx*yy + yx*zy + zx*xy - zx*yy - xx*zy - yx*xy);
} 
- (double) signum: (double)x {
	if (x == 0) {
		return 0;
	} else if (x < 0) {
		return -1;
	} else {
		return 1;
	}
}	
- (double) crossingAX:(double)ax AY:(double) ay BX:(double)bx BY:(double)by{
	if (([self ownerityXX:px XY:py YX:rx YY:ry ZX:ax ZY:ay] == 0) && 
		([self ownerityXX:px XY:py YX:rx YY:ry ZX:bx ZY:by] == 0))	{ 
		
		if (([self signum:[self detXX:px XY:py YX:rx YY:ry ZX:ax ZY:ay]]) != ([self signum:[self detXX:px XY:py YX:rx YY:ry ZX:bx ZY:by]]) &&
			([self signum:[self detXX:ax XY:ay YX:bx YY:by ZX:px ZY:py]]) != ([self signum:[self detXX:ax XY:ay YX:bx YY:by ZX:rx ZY:ry]]))
			return 1;
		else
			return 0;
	}
	else {
		
		if (([self ownerityXX:px XY:py YX:rx YY:ry ZX:ax ZY:ay] == 1) && 
			([self ownerityXX:px XY:py YX:rx YY:ry ZX:bx ZY:by]==1)) {
			if ([self signum:[self detXX:px XY:py YX:rx YY:ry 
									  ZX:[[polygonx objectAtIndex:(k-1+n)%n] doubleValue]
									  ZY:[[polygony objectAtIndex:(k-1+n)%n] doubleValue]]] == 
				[self signum:[self detXX:px XY:py YX:rx YY:ry 
									  ZX:[[polygonx objectAtIndex:(k+2)%n] doubleValue]
									  ZY:[[polygony objectAtIndex:(k+2)%n] doubleValue]]] &&
				[self signum:[self detXX:px XY:py YX:rx YY:ry 
									  ZX:[[polygonx objectAtIndex:(k-1+n)%n] doubleValue] 
									  ZY:[[polygony objectAtIndex:(k-1+n)%n] doubleValue]]] != 0)
				return 0;
			else
				return 1;
		} else {
	      	if ([self ownerityXX:px XY:py YX:rx YY:ry 
							  ZX:[[polygonx objectAtIndex:(k-1+n)%n] doubleValue] 
							  ZY:[[polygony objectAtIndex:(k-1+n)%n] doubleValue]] == 1 || 
	      		([self ownerityXX:px XY:py YX:rx YY:ry 
							   ZX:[[polygonx objectAtIndex:(k+2)%n] doubleValue] 
							   ZY:[[polygony objectAtIndex:(k+2)%n] doubleValue]]) == 1)
	      		return 0;
	       	else {
	       		//polprosta zawiera tylko wierzcholek
	         	if ([self ownerityXX:px XY:py YX:rx YY:ry ZX:bx ZY:by] == 1) {
					tmpx = ax;
					tmpy = ay;
					return 0;
	         	}
	            if ([self ownerityXX:px XY:py YX:rx YY:ry ZX:ax ZY:ay] == 1){
	            	if ([self signum:[self detXX:px XY:py YX:rx YY:ry ZX:tmpx ZY:tmpy]] == [self signum:[self detXX:px XY:py YX:rx YY:ry ZX:bx ZY:by]] &&
	            		[self signum:[self detXX:px XY:py YX:rx YY:ry ZX:tmpx ZY:tmpy]] != 0) 
	            		return 0;
	            	else
	            		return 1;
	            }
			}
		}
	}
	return 0;	
}
- (BOOL) isInside {
	int l=0; //liczba przeciec
	int i;
	
	for (i=0; i<n; i++) {
		k=i;
		if ([self ownerityXX:[[polygonx objectAtIndex:i] doubleValue]
						  XY:[[polygony objectAtIndex:i] doubleValue]
						  YX:[[polygonx objectAtIndex:(i+1)%n] doubleValue]
						  YY:[[polygony objectAtIndex:(i+1)%n] doubleValue]
						  ZX:px 
						  ZY:py] == 1) {
			return YES;
		}
		if ([self crossingAX:[[polygonx objectAtIndex:i] doubleValue]
						  AY:[[polygony objectAtIndex:i] doubleValue] 
						  BX:[[polygonx objectAtIndex:(i+1)%n] doubleValue] 
						  BY:[[polygony objectAtIndex:(i+1)%n] doubleValue]] == 1)
			l++;
	}
	if ((l % 2) == 0) 
		return NO;	
	else
		return YES;
}
- (CLLocation*) givePolygonCenter {
	double lonSum = 0;
	double latSum = 0;
	for (NSNumber* lon in polygonx) {
		lonSum += [lon doubleValue];
	}
	for (NSNumber* lat in polygony) {
		latSum += [lat doubleValue];
	}
	double lon = (lonSum / [polygonx count]) - 180;
	double lat = (latSum / [polygony count]) - 180;
	CLLocation* loc = [[[CLLocation alloc] initWithLatitude:lat longitude:lon] autorelease];
	return loc;
}
- (void)dealloc {
	[super dealloc];
	[polygonx release];
	[polygony release];
}	
@end
