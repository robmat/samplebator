#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "SharepointListsTVC.h"
#import "SoapRequest.h"

@interface SharepointListsVC : VCBase <SoapRequestDelegate> {
	IBOutlet UITableView* tableView;
	SharepointListsTVC* tableVC;
	NSMutableDictionary* listsData;
	NSMutableDictionary* titletoNameDict;
	NSString* categoryPressed;
	NSString* myListName;
}

@property (nonatomic,retain) IBOutlet UITableView* tableView;
@property (nonatomic,retain) SharepointListsTVC* tableVC;
@property (nonatomic,retain) NSMutableDictionary* listsData;
@property (nonatomic,retain) NSMutableDictionary* titletoNameDict;

- (IBAction) presentationsAction: (id) sender;
- (IBAction) progressAction: (id) sender;
- (IBAction) reportsAction: (id) sender;
- (IBAction) documentsAction: (id) sender;
- (void) defaultListItemclickAction: (NSString*) actionStr;

@end
