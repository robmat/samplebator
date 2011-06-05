#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>

@interface LogsListViewController : UITableViewController <MFMailComposeViewControllerDelegate> {

}

- (NSString*) prepareBody: (NSString*) body withItems: (NSArray*) items;

@end
