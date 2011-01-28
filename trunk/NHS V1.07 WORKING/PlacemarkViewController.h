

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>

@interface PlacemarkViewController : UIViewController <UITableViewDelegate, UITableViewDataSource> {
    MKPlacemark *placemark;
    UITableView *tableView;
}

@property (nonatomic, retain) MKPlacemark *placemark;
@property (nonatomic, retain) IBOutlet UITableView *tableView;

- (IBAction)done;

@end
