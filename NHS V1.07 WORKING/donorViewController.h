//
//  donorViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 12/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface donorViewController : UIViewController {

	IBOutlet UISwitch * donorYESorNo ;
	IBOutlet UITextField * numberField ;
	
}

@property ( readwrite , assign ) UISwitch * donorYESorNo ;


-(IBAction)buttonSwitchPressed ;
-(IBAction)saveAction:(id)sender ;


-(NSString *) dataFilePath ;


@end
