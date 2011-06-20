#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import "CommonViewControllerBase.h"

@interface ContactViewController : CommonViewControllerBase <MFMailComposeViewControllerDelegate> {

}

- (IBAction) mail1Action: (id) sender;
- (IBAction) mail2Action: (id) sender;
- (IBAction) websiteAction: (id) sender;
- (IBAction) disclaimerAction: (id) sender;

@end
