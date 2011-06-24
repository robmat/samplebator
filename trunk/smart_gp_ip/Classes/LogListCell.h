
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>

@interface LogListCell : UITableViewCell {
	
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* detailLbl;
	NSDictionary* log;
	UINavigationController* navController;
	UITableViewController* tvc;
	UILocalNotification* locNot;
@public
	BOOL reminder;
}

@property (nonatomic, retain) UILabel* titleLbl;
@property (nonatomic, retain) UILabel* detailLbl;
@property (nonatomic, retain) NSDictionary* log;
@property (nonatomic, retain) UINavigationController* navController;
@property (nonatomic, retain) UITableViewController* tvc;
@property (nonatomic, retain) UILocalNotification* locNot;

- (IBAction) editAction: (id) sender;
- (IBAction) deleteAction: (id) sender;

@end
