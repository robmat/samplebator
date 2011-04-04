//
//  MapViewcontroller.m
//  rac_ip
//
//  Created by User on 3/24/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "MapViewcontroller.h"
#import <MapKit/MapKit.h>
#import "MyAnnotation.h"
#import "VoucherViewcontroller.h"

@implementation MapViewcontroller

/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if ((self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil])) {
        // Custom initialization
    }
    return self;
}
*/


- (void)viewDidLoad {
    [super viewDidLoad];
	
	map.showsUserLocation = YES;
	double latitude = map.userLocation.coordinate.latitude;
    double longtitude  = map.userLocation.coordinate.longitude;
	
	MKCoordinateRegion region;
	region.center.latitude  = latitude;
	region.center.longitude = longtitude;
	MKCoordinateSpan span;
	span.latitudeDelta  = 0.009;
	span.longitudeDelta = 0.009;
	region.span = span;
	[map setRegion:region animated:YES];
	
	latitude = 51.453751;
	longtitude = -2.59483;
	
	NSString* latStr = [[NSNumber numberWithDouble:latitude] stringValue];
	NSString* lonStr = [[NSNumber numberWithDouble:longtitude] stringValue];
	
	MyAnnotation* anno = [[MyAnnotation alloc] initWithDictionary:[NSDictionary dictionaryWithObjectsAndKeys:
																   @"ETON Bar and Nightclub", @"name",
																   latStr, @"latitude", 
																   lonStr, @"longtitude", 
																   @"28 Baldwin Street, Bristol BS1 1NL", @"postcode",
																   nil]];
	[map addAnnotation:anno];
	[anno release];
}
- (MKAnnotationView *) mapView:(MKMapView *)mapView viewForAnnotation:(id <MKAnnotation>)annotation{
	if (map.userLocation == annotation){
		return nil;
	}
	NSString *identifier = @"MY_IDENTIFIER";
	MKAnnotationView *annotationView = [map dequeueReusableAnnotationViewWithIdentifier:identifier];
	if (annotationView == nil){
		annotationView = [[[MKAnnotationView alloc] initWithAnnotation:annotation reuseIdentifier:identifier] autorelease];
		annotationView.image = [UIImage imageNamed:@"map_annotation.png"];
		annotationView.canShowCallout = YES;
		annotationView.rightCalloutAccessoryView = [UIButton buttonWithType:UIButtonTypeDetailDisclosure];
		//annotationView.leftCalloutAccessoryView =  [[[UIImageView  alloc] initWithImage:[UIImage imageNamed:@"whitelogo.png"]] autorelease];
	}
	return annotationView;
}
- (void) mapView:(MKMapView *)mapView annotationView:(MKAnnotationView *)view calloutAccessoryControlTapped:(UIControl *)control {
	VoucherViewcontroller* vvc = [[VoucherViewcontroller alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:vvc animated:YES];
	[vvc release];
}
- (IBAction) backAction: (id) sender {
	[self.navigationController popViewControllerAnimated:YES];
}
/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}


@end
