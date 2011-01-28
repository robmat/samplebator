

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>


@interface dentalMAP : UIViewController <MKMapViewDelegate, UIAlertViewDelegate, UIActionSheetDelegate, UISearchBarDelegate> {

	IBOutlet UILabel     *  viewDetailName ;
	IBOutlet UIView      *  viewDetail ;
	IBOutlet MKMapView   *  mapa ;
	IBOutlet UISearchBar *  searchBar ;
	IBOutlet UILabel     *  labelLatitude ;
	
	float minLatitude ;
	float maxLatitude ;
	
	float minLongitude ;
	float maxLongitude ;
	
	NSArray * dentalLocations ;
	
	BOOL avanzar ;
	
}

@property (nonatomic, retain) IBOutlet UISearchBar * searchBar ;


-(NSString *) dataFilePathDental ;
-(IBAction)goHome:(id)sender ;



@end