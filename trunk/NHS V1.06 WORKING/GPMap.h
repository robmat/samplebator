

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>

@interface GPMap : UIViewController <MKMapViewDelegate, UIAlertViewDelegate, UIActionSheetDelegate, UISearchBarDelegate> {

	IBOutlet UILabel     *  viewDetailName ;
	IBOutlet UIView      *  viewDetail ;
	IBOutlet MKMapView   *  mapa ;
	IBOutlet UISearchBar *  searchBar ;
	IBOutlet UILabel     *  labelLatitude ;
	
	float minLatitude ;
	float maxLatitude ;
	
	float minLongitude ;
	float maxLongitude ;
	
	NSArray * gpLocations ;
	
	BOOL avanzar ;
}

@property (nonatomic, retain) IBOutlet UISearchBar * searchBar ;


-(NSString *) dataFilePathGP ;
-(IBAction)WhereAmIButton:(id)sender ;
-(IBAction)goHome:(id)sender ;

@end