#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequestDelegate.h"

@interface MyFavJobsVC : VCBase <ASIHTTPRequestDelegate, UITableViewDelegate, UITableViewDataSource, UIAlertViewDelegate> {
	
	CXMLDocument* doc;
	IBOutlet UITableView* tableView;
	NSString* deleteFavJobId;
}

@property(nonatomic,retain) CXMLDocument* doc;
@property(nonatomic,retain) IBOutlet UITableView* tableView;
@property(nonatomic,retain) NSString* deleteFavJobId;

- (IBAction) editAction: (id) sender;
- (void) redButtonAction: (NSString*) jobId;

@end
