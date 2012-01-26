#import <UIKit/UIKit.h>
#import "SharepointListTVC.h"
#import "VCBase.h"
#import <MapKit/MapKit.h>

@interface SharepointListVC : VCBase <UIImagePickerControllerDelegate, UIAlertViewDelegate> {
	
	IBOutlet UITableView* tableView;
	SharepointListTVC* sltvc;
	NSMutableDictionary* listsData;
	NSDateFormatter* dateFrmt;
	NSString* myListName;
	NSString* titleStr;
	NSString* currentFolder;
	NSString* tempTitle;
    IBOutlet MKMapView* map;
    NSError* error;
    IBOutlet UIActivityIndicatorView* indicator;
    IBOutlet UIView* indicatorBack;
}

@property (nonatomic,retain) IBOutlet UITableView* tableView;
@property (nonatomic,retain) SharepointListTVC* sltvc;
@property (nonatomic,retain) NSMutableDictionary* listsData;
@property (nonatomic,retain) NSString* myListName;
@property (nonatomic,retain) NSString* titleStr;
@property (nonatomic,retain) NSString* currentFolder;
@property (nonatomic,retain) IBOutlet MKMapView* map;
@property (nonatomic,retain) NSError* error;
@property (nonatomic,retain) IBOutlet UIActivityIndicatorView* indicator;
@property (nonatomic,retain) IBOutlet UIView* indicatorBack;

- (IBAction)sortTypeAction: (id) sender;
- (IBAction)sortTitleAction: (id) sender;
- (IBAction)sortCreatedAction: (id) sender;
- (IBAction)sortModifiedAction: (id) sender;
- (NSData*)enterGpsData: (NSData*) imgData;

@end
